<?php declare(strict_types=1);

namespace Drago\Database;

use Dibi\Connection;
use Dibi\Exception;
use Dibi\Result;
use Dibi\Row;
use function is_array, sprintf;


/**
 * One table of the database: a repository creates it in its constructor and works through it,
 * new Table($connection, ArticleEntity::Table, ArticleEntity::PrimaryKey, ArticleEntity::class).
 * Records are fetched as the entity, without one as Dibi rows; a table without a primary key (a view)
 * cannot get or save a record by it.
 *
 * @template T of Row = Row
 */
readonly class Table
{
	public function __construct(
		private Connection $connection,
		public string $name,
		public ?string $primaryKey = null,
		/** @var class-string<T> the class the records are fetched as */
		public string $entity = Row::class,
	) {
	}


	public function getConnection(): Connection
	{
		return $this->connection;
	}


	/**
	 * Creates a new query builder that fetches records as the entity.
	 * @return ExtraFluent<T>
	 */
	#[\NoDiscard]
	public function command(): ExtraFluent
	{
		/** @var ExtraFluent<T> $fluent */
		$fluent = new ExtraFluent($this->connection);
		$fluent->className = $this->entity;
		return $fluent;
	}


	/**
	 * Read records from the table.
	 * @return ExtraFluent<T>
	 */
	#[\NoDiscard]
	public function read(mixed ...$args): ExtraFluent
	{
		return $this->command()
			->select(...$args)
			->from($this->name);
	}


	/**
	 * Finds records by a column value, or by more columns given as [column => value].
	 * @param string|array<string, mixed> $column
	 * @return ExtraFluent<T>
	 */
	#[\NoDiscard]
	public function find(string|array $column, mixed $args = null): ExtraFluent
	{
		return $this->whereColumns($this->read('*'), $column, $args);
	}


	/**
	 * Returns a record by its primary key.
	 * @return ExtraFluent<T>
	 * @throws \LogicException
	 */
	#[\NoDiscard]
	public function get(int $id): ExtraFluent
	{
		return $this->read('*')
			->where('%n = ?', $this->getPrimaryKey(), $id);
	}


	/**
	 * Counts the records of the table, all of them or those matching the condition (as for where()).
	 * @throws Exception
	 */
	public function count(mixed ...$cond): int
	{
		$query = $this->read('COUNT(*)');
		if ($cond !== []) {
			$query->where(...$cond);
		}

		return (int) $query->fetchSingle();
	}


	/**
	 * Deletes records by a column value, or by more columns given as [column => value];
	 * an empty array is refused, it would delete the whole table.
	 * @param string|array<string, mixed> $column
	 * @return ExtraFluent<T>
	 * @throws \InvalidArgumentException
	 */
	#[\NoDiscard]
	public function delete(string|array $column, mixed $args = null): ExtraFluent
	{
		if ($column === []) {
			throw new \InvalidArgumentException('Deleting needs a condition, an empty array would delete all records.');
		}

		$query = $this->command()
			->delete()
			->from($this->name);
		return $this->whereColumns($query, $column, $args);
	}


	/**
	 * Insert a new record into the table; an entity, or camelCase keys inserted into their snake_case columns.
	 * @param Entity|array<string, mixed> $args
	 * @return ExtraFluent<T>
	 */
	#[\NoDiscard]
	public function insert(Entity|array $args): ExtraFluent
	{
		$args = $args instanceof Entity
			? $args->toColumns()
			: Entity::toColumnNames($args);
		return $this->command()
			->insert()
			->into($this->name, '(%n)', array_keys($args))
			->values('%l', $args);
	}


	/**
	 * Update records in the table; an entity, or camelCase keys set into their snake_case columns.
	 * @param Entity|array<string, mixed> $args
	 * @return ExtraFluent<T>
	 */
	#[\NoDiscard]
	public function update(Entity|array $args): ExtraFluent
	{
		return $this->command()
			->update($this->name)
			->set($args instanceof Entity ? $args->toColumns() : Entity::toColumnNames($args));
	}


	/**
	 * Insert or update a record; camelCase keys (form values) are saved into their snake_case columns.
	 * @param Entity|iterable<string, mixed> $args
	 * @throws Exception
	 * @throws \LogicException
	 */
	public function save(Entity|iterable $args): Result|int|null
	{
		$key = $this->getPrimaryKey();

		if ($args instanceof Entity) {
			$data = $args->toColumns();
			if ($args instanceof EntityOracle) {
				$key = strtoupper($key);
			}

		} else {
			/** @var array<string, mixed> $values */
			$values = $args instanceof \Traversable
				? iterator_to_array($args)
				: (array) $args;
			// the primary key is looked up by its column name
			$data = Entity::toColumnNames($values);
		}

		$id = $data[$key] ?? null;
		unset($data[$key]);

		if ($id > 0) {
			$query = $this->update($data)
				->where('%n = ?', $key, $id);

		} else {
			$query = $this->insert($data);
		}

		return $query->execute();
	}


	/**
	 * Returns the ID of the last inserted record.
	 * @throws Exception
	 */
	public function getInsertId(?string $sequence = null): int
	{
		return $this->connection->getInsertId($sequence);
	}


	/**
	 * Runs the callback in a transaction: commits after it, rolls back and rethrows when it throws.
	 * A transaction inside another one joins it, so only the outer one commits.
	 * @template R
	 * @param callable(Connection): R $callback
	 * @return R
	 * @throws \Throwable
	 */
	public function transaction(callable $callback): mixed
	{
		return $this->connection->transaction($callback);
	}


	/**
	 * Begins a transaction (optionally with savepoint).
	 * @throws Exception
	 */
	public function beginTransaction(?string $savepoint = null): void
	{
		$this->connection->begin($savepoint);
	}


	/**
	 * Commits the transaction (optionally to a savepoint).
	 * @throws Exception
	 */
	public function commit(?string $savepoint = null): void
	{
		$this->connection->commit($savepoint);
	}


	/**
	 * Rolls back the transaction (optionally to a savepoint).
	 * @throws Exception
	 */
	public function rollBack(?string $savepoint = null): void
	{
		$this->connection->rollback($savepoint);
	}


	/**
	 * Returns the primary key; a table without one (a view) cannot get or save a record by it.
	 * @throws \LogicException
	 */
	public function getPrimaryKey(): string
	{
		return $this->primaryKey
			?? throw new \LogicException(sprintf('The table %s has no primary key.', $this->name));
	}


	/**
	 * Adds the condition of one column, or of more columns given as [column => value].
	 * @param ExtraFluent<T> $query
	 * @param string|array<string, mixed> $column
	 * @return ExtraFluent<T>
	 */
	private function whereColumns(ExtraFluent $query, string|array $column, mixed $args): ExtraFluent
	{
		return is_array($column)
			? $query->where($column)
			: $query->where('%n = ?', $column, $args);
	}
}
