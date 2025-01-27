<?php

declare(strict_types=1);

/**
 * Drago Extension
 * Package built on Nette Framework
 */

namespace Drago\Database;

use Dibi\Connection;
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
	 * @param string|int $args The value to match against the column.
	 * @return ExtraFluent<T> The fluent query builder with the where condition.
	 * @throws AttributeDetectionException If the table name or class is not defined.
	 */
	public function find(string $column, string|int $args): ExtraFluent
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
	 * @param string|int $args The value to match against the column.
	 * @return ExtraFluent The fluent query builder for deleting the record.
	 * @throws AttributeDetectionException If the table name or class is not defined.
	 */
	public function delete(string $column, string|int $args): ExtraFluent
	{
		return $this->command()
			->delete()
			->from($this->getTableName())
			->where('%n = ?', $column, $args);
	}


	/**
	 * Insert or update a record.
	 *
	 * @param mixed $values The values to insert or update in the table.
	 * @return Result|int|null The result of the query execution.
	 * @throws AttributeDetectionException If the table name or class is not defined.
	 * @throws Exception If an error occurs while executing the query.
	 */
	public function save(mixed $values): Result|int|null
	{
		$key = $this->getPrimaryKey();
		$table = $this->getTableName();

		// Convert entity to array if necessary
		if ($values instanceof Entity) {
			$values = $values->toArray();
		} elseif ($values instanceof EntityOracle) {
			$values = $values->toArrayUpper();
			$key = strtoupper($key);
		}

		$id = $values[$key] ?? null;
		$query = $id > 0
			? $this->getConnection()->update($table, $values)->where('%n = ?', $key, $id)
			: $this->getConnection()->insert($table, $values);

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
}
