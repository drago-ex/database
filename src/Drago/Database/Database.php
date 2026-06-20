<?php

declare(strict_types=1);

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
	public function command(): ExtraFluent
	{
		/** @temp ExtraFluent<T> $fluent */
		$fluent = new ExtraFluent($this->getConnection());

		/** @temp class-string<T>|null $className */
		$className = $this->getClassName();
		$fluent->className = $className;
		return $fluent;
	}


	/**
	 * Read records from the table.
	 * @return ExtraFluent<T>
	 * @throws AttributeDetectionException
	 */
	public function read(mixed ...$args): ExtraFluent
	{
		return $this->command()
			->select(...$args)
			->from($this->getTableName());
	}


	/**
	 * Finds records by column name.
	 * @return ExtraFluent<T>
	 * @throws AttributeDetectionException
	 */
	public function find(string $column, mixed $args): ExtraFluent
	{
		return $this->read('*')
			->where('%n = ?', $column, $args);
	}


	/**
	 * Returns a record by its primary key.
	 * @return ExtraFluent<T>
	 * @throws AttributeDetectionException
	 */
	public function get(int $id): ExtraFluent
	{
		return $this->read('*')
			->where('%n = ?', $this->getPrimaryKey(), $id);
	}


	/**
	 * Deletes a record by a specific column value.
	 * @return ExtraFluent<T>
	 * @throws AttributeDetectionException
	 */
	public function delete(string $column, mixed $args): ExtraFluent
	{
		return $this->command()
			->delete()
			->from($this->getTableName())
			->where('%n = ?', $column, $args);
	}


	/**
	 * Insert a new record into the table.
	 * @param array<string, mixed> $args
	 * @return ExtraFluent<T>
	 * @throws AttributeDetectionException
	 */
	public function insert(array $args): ExtraFluent
	{
		return $this->command()
			->insert()
			->into($this->getTableName(), '(%n)', array_keys($args))
			->values('%l', $args);
	}


	/**
	 * Update records in the table.
	 * @param array<string, mixed> $args
	 * @return ExtraFluent<T>
	 * @throws AttributeDetectionException
	 */
	public function update(array $args): ExtraFluent
	{
		return $this->command()
			->update($this->getTableName())
			->set($args);
	}


	/**
	 * Insert or update a record.
	 * @param Entity|EntityOracle|iterable<string, mixed> $args
	 * @throws AttributeDetectionException
	 * @throws Exception
	 */
	public function save(Entity|EntityOracle|iterable $args): Result|int|null
	{
		$key = $this->getPrimaryKey();

		if ($args instanceof EntityOracle) {
			$data = $args->toArrayUpper();
			$key = strtoupper($key);

		} else {

			/** @temp array<string, mixed> $data */
			$data = $args instanceof \Traversable
				? iterator_to_array($args)
				: (array) $args;
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
}
