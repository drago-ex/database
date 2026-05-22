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
 * @template T
 * @property-read Connection $connection
 */
trait Database
{
	use AttributeDetection;

	/** Get the database connection. */
	public function getConnection(): Connection
	{
		return $this->connection;
	}


	/**
	 * Create a new ExtraFluent query builder.
	 * @return ExtraFluent<T>
	 * @throws AttributeDetectionException
	 */
	public function command(): ExtraFluent
	{
		/** @var ExtraFluent<T> $fluent */
		$fluent = new ExtraFluent($this->getConnection());

		/** @var class-string<Row>|null $className */
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
	 * Find records by column name.
	 * @return ExtraFluent<T>
	 * @throws AttributeDetectionException
	 */
	public function find(string $column, mixed $args): ExtraFluent
	{
		return $this->read('*')
			->where('%n = ?', $column, $args);
	}


	/**
	 * Get a record by its primary key.
	 * @return ExtraFluent<T>
	 * @throws AttributeDetectionException
	 */
	public function get(int $id): ExtraFluent
	{
		return $this->read('*')
			->where('%n = ?', $this->getPrimaryKey(), $id);
	}


	/**
	 * Delete a record by a specific column value.
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
	 * @param iterable<string, mixed> $args
	 * @return ExtraFluent<T>
	 * @throws AttributeDetectionException
	 */
	public function insert(iterable $args): ExtraFluent
	{
		return $this->command()
			->insert()
			->into($this->getTableName())
			->values($args);
	}


	/**
	 * Update records in the table.
	 * @param iterable<string, mixed> $args
	 * @return ExtraFluent<T>
	 * @throws AttributeDetectionException
	 */
	public function update(iterable $args): ExtraFluent
	{
		return $this->command()
			->update($this->getTableName())
			->set($args);
	}


	/**
	 * Insert or update a record.
	 * @param iterable<string, mixed> $args
	 * @throws AttributeDetectionException
	 * @throws Exception
	 */
	public function save(iterable $args): Result|int|null
	{
		$key = $this->getPrimaryKey();
		if ($args instanceof EntityOracle) {
			$args = $args->toArrayUpper();
			$key = strtoupper($key);
		}

		$data = $args instanceof \Traversable
			? iterator_to_array($args)
			: $args;

		$id = $data[$key] ?? null;

		$query = $id > 0
			? $this->update($args)->where('%n = ?', $key, $id)
			: $this->insert($args);

		return $query->execute();
	}


	/**
	 * Get the id of the last inserted record.
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
