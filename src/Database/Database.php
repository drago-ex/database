<?php declare(strict_types=1);

namespace Drago\Database;

use Dibi\Connection;
use Dibi\Exception;
use Dibi\Result;
use Dibi\Row;
use Drago\Attr\AttributeDetection;
use Drago\Attr\AttributeDetectionException;


/**
 * @template T of Row
 * @property-read Connection $connection
 */
trait Database
{
	use AttributeDetection;

	public function getConnection(): Connection
	{
		return $this->connection;
	}


	/**
	 * Creates a new ExtraFluent query builder.
	 * @return ExtraFluent<T>
	 * @throws AttributeDetectionException
	 */
	#[\NoDiscard]
	public function command(): ExtraFluent
	{
		/** @var ExtraFluent<T> $fluent */
		$fluent = new ExtraFluent($this->getConnection());

		/** @var class-string<T>|null $className */
		$className = $this->getEntityClassName();
		$fluent->className = $className;
		return $fluent;
	}


	/**
	 * Read records from the table.
	 * @return ExtraFluent<T>
	 * @throws AttributeDetectionException
	 */
	#[\NoDiscard]
	public function read(mixed ...$args): ExtraFluent
	{
		return $this->command()
			->select(...$args)
			->from($this->getTableName());
	}


	/**
	 * Finds records by a column value, or by more columns given as [column => value].
	 * @param string|array<string, mixed> $column
	 * @return ExtraFluent<T>
	 * @throws AttributeDetectionException
	 */
	#[\NoDiscard]
	public function find(string|array $column, mixed $args = null): ExtraFluent
	{
		return $this->whereColumns($this->read('*'), $column, $args);
	}


	/**
	 * Returns a record by its primary key.
	 * @return ExtraFluent<T>
	 * @throws AttributeDetectionException
	 */
	#[\NoDiscard]
	public function get(int $id): ExtraFluent
	{
		return $this->read('*')
			->where('%n = ?', $this->getPrimaryKey(), $id);
	}


	/**
	 * Counts the records of the table, all of them or those matching the condition (as for where()).
	 * @throws AttributeDetectionException
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
	 * @throws AttributeDetectionException
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
			->from($this->getTableName());
		return $this->whereColumns($query, $column, $args);
	}


	/**
	 * Insert a new record into the table; an entity, or camelCase keys inserted into their snake_case columns.
	 * @param Entity|array<string, mixed> $args
	 * @return ExtraFluent<T>
	 * @throws AttributeDetectionException
	 */
	#[\NoDiscard]
	public function insert(Entity|array $args): ExtraFluent
	{
		$args = $args instanceof Entity
			? $args->toColumns()
			: Entity::toColumnNames($args);
		return $this->command()
			->insert()
			->into($this->getTableName(), '(%n)', array_keys($args))
			->values('%l', $args);
	}


	/**
	 * Update records in the table; an entity, or camelCase keys set into their snake_case columns.
	 * @param Entity|array<string, mixed> $args
	 * @return ExtraFluent<T>
	 * @throws AttributeDetectionException
	 */
	#[\NoDiscard]
	public function update(Entity|array $args): ExtraFluent
	{
		return $this->command()
			->update($this->getTableName())
			->set($args instanceof Entity ? $args->toColumns() : Entity::toColumnNames($args));
	}


	/**
	 * Insert or update a record; camelCase keys (form values) are saved into their snake_case columns.
	 * @param Entity|iterable<string, mixed> $args
	 * @throws AttributeDetectionException
	 * @throws Exception
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
		return $this->getConnection()
			->getInsertId($sequence);
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
		return $this->getConnection()
			->transaction($callback);
	}


	/**
	 * Begins a transaction (optionally with savepoint).
	 * @throws Exception
	 */
	public function beginTransaction(?string $savepoint = null): void
	{
		$this->getConnection()
			->begin($savepoint);
	}


	/**
	 * Commits the transaction (optionally to a savepoint).
	 * @throws Exception
	 */
	public function commit(?string $savepoint = null): void
	{
		$this->getConnection()
			->commit($savepoint);
	}


	/**
	 * Rolls back the transaction (optionally to a savepoint).
	 * @throws Exception
	 */
	public function rollBack(?string $savepoint = null): void
	{
		$this->getConnection()
			->rollback($savepoint);
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
