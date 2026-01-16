<?php

declare(strict_types=1);

/**
 * Drago Extension
 * Package built on Nette Framework
 */

namespace Drago\Database;

use Dibi\Connection;
use Dibi\DriverException;
use Dibi\Exception;
use Dibi\Result;
use Drago\Attr\AttributeDetection;
use Drago\Attr\AttributeDetectionException;


/**
 * @template T
 * @property-read Connection $connection
 */
trait Database
{
	use AttributeDetection;

	/**
	 * Get the database connection.
	 *
	 * @return Connection The database connection instance.
	 */
	public function getConnection(): Connection
	{
		return $this->connection;
	}


	/**
	 * Create a new ExtraFluent query builder.
	 *
	 * @return ExtraFluent<T> A new instance of ExtraFluent to build queries.
	 * @throws AttributeDetectionException If the table name or class is not defined.
	 */
	public function command(): ExtraFluent
	{
		$fluent = new ExtraFluent($this->getConnection());
		$fluent->className = $this->getClassName();
		return $fluent;
	}


	/**
	 * Read records from the table.
	 *
	 * @param mixed ...$args Arguments for selecting columns.
	 * @return ExtraFluent<T> The fluent query builder with the select statement.
	 * @throws AttributeDetectionException If the table name or class is not defined.
	 */
	public function read(...$args): ExtraFluent
	{
		return $this->command()
			->select(...$args)
			->from($this->getTableName());
	}


	/**
	 * Find records by column name.
	 *
	 * @param string $column The column name to search.
	 * @param mixed $args The value to match against the column.
	 * @return ExtraFluent<T> The fluent query builder with the where condition.
	 * @throws AttributeDetectionException If the table name or class is not defined.
	 */
	public function find(string $column, mixed $args): ExtraFluent
	{
		return $this->read('*')
			->where('%n = ?', $column, $args);
	}


	/**
	 * Get a record by its primary key.
	 *
	 * @param int $id The primary key of the record to fetch.
	 * @return ExtraFluent<T> The fluent query builder for fetching the record.
	 * @throws AttributeDetectionException If the table name or class is not defined.
	 */
	public function get(int $id): ExtraFluent
	{
		return $this->read('*')
			->where('%n = ?', $this->getPrimaryKey(), $id);
	}


	/**
	 * Delete a record by a specific column value.
	 *
	 * @param string $column The column name to search by.
	 * @param mixed $args The value to match against the column.
	 * @return ExtraFluent The fluent query builder for deleting the record.
	 * @throws AttributeDetectionException If the table name or class is not defined.
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
	 *
	 * @param array|iterable $args Values to insert (associative array: column => value).
	 * @return ExtraFluent<T> The fluent query builder for inserting the record.
	 * @throws AttributeDetectionException If the table name or class is not defined.
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
	 *
	 * @param array|iterable $args Values to update (associative array: column => value).
	 * @return ExtraFluent<T> The fluent query builder for updating records.
	 * @throws AttributeDetectionException If the table name or class is not defined.
	 */
	public function update(iterable $args): ExtraFluent
	{
		return $this->command()
			->update($this->getTableName())
			->set($args);
	}


	/**
	 * Insert or update a record.
	 *
	 * @param array|iterable $args The values to insert or update in the table.
	 * @return Result|int|null The result of the query execution.
	 * @throws AttributeDetectionException If the table name or class is not defined.
	 * @throws Exception If an error occurs while executing the query.
	 */
	public function save(iterable $args): Result|int|null
	{
		$key = $this->getPrimaryKey();
		if ($args instanceof EntityOracle) {
			$args = $args->toArrayUpper();
			$key = strtoupper($key);
		}

		$id = $args[$key] ?? null;

		$query = $id > 0
			? $this->update($args)->where('%n = ?', $key, $id)
			: $this->insert($args);

		return $query->execute();
	}


	/**
	 * Get the id of the last inserted record.
	 *
	 * @param string|null $sequence The sequence name (optional).
	 * @return int The id of the last inserted record.
	 * @throws Exception If an error occurs while fetching the insert id.
	 */
	public function getInsertId(?string $sequence = null): int
	{
		return $this->getConnection()
			->getInsertId($sequence);
	}


	/**
	 * Begins a transaction (optionally with savepoint).
	 * @param string|null $savepoint Optional savepoint name.
	 * @throws DriverException If the driver doesn't support transactions.
	 */
	public function beginTransaction(?string $savepoint = null): void
	{
		$this->getConnection()
			->begin($savepoint);
	}


	/**
	 * Commits the transaction (optionally to a savepoint).
	 * @param string|null $savepoint Optional savepoint name.
	 * @throws DriverException If commit fails.
	 */
	public function commit(?string $savepoint = null): void
	{
		$this->getConnection()
			->commit($savepoint);
	}


	/**
	 * Rolls back the transaction (optionally to a savepoint).
	 * @param string|null $savepoint Optional savepoint name.
	 * @throws DriverException If rollback fails.
	 */
	public function rollBack(?string $savepoint = null): void
	{
		$this->getConnection()
			->rollback($savepoint);
	}
}
