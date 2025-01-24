<?php

declare(strict_types=1);

/**
 * Drago Extension
 * Package built on Nette Framework
 */

namespace Drago\Database;

use Dibi\Connection;
use Dibi\Exception;
use Dibi\Fluent;
use Dibi\Result;
use Drago\Attr\AttributeDetection;
use Drago\Attr\AttributeDetectionException;


/**
 * Repository base.
 * Provides basic CRUD operations for entities in the database.
 *
 * @property-read Connection $db The database connection used for queries.
 */
trait Repository
{
	use AttributeDetection;

	/**
	 * Retrieves records from a table based on the provided conditions.
	 *
	 * @param mixed ...$cond Conditions to apply to the query (e.g. column = value).
	 * @return Fluent A fluent query builder instance.
	 * @throws AttributeDetectionException If attribute detection fails.
	 */
	public function table(...$cond): Fluent
	{
		$query = $this->db->select('*')
			->from($this->getTableName());

		if ($cond) {
			$query->where(...$cond);
		}

		return $query;
	}


	/**
	 * Finds a record by its primary key (ID).
	 *
	 * @param int $id The ID of the record to retrieve.
	 * @return Fluent A fluent query builder instance.
	 * @throws AttributeDetectionException If attribute detection fails.
	 */
	public function get(int $id): Fluent
	{
		return $this->table('%n = ?', $this->getPrimaryKey(), $id);
	}


	/**
	 * Deletes a record by its primary key (ID).
	 *
	 * @param int $id The ID of the record to delete.
	 * @return Result|int|null The result of the delete operation (number of affected rows or result object).
	 * @throws Exception If a database error occurs.
	 * @throws AttributeDetectionException If attribute detection fails.
	 */
	public function remove(int $id): Result|int|null
	{
		return $this->db->delete($this->getTableName())
			->where('%n = ?', $this->getPrimaryKey(), $id)
			->execute();
	}


	/**
	 * Saves a record in the database (either insert or update).
	 *
	 * @param mixed $values The data to insert or update.
	 * @return Result|int|null The result of the insert or update operation.
	 * @throws Exception If a database error occurs.
	 * @throws AttributeDetectionException If attribute detection fails.
	 */
	public function put(mixed $values): Result|int|null
	{
		$key = $this->getPrimaryKey();

		// Convert entity to array format if it's an instance of Entity or EntityOracle
		if ($values instanceof Entity) {
			$values = $values->toArray();
		} elseif ($values instanceof EntityOracle) {
			$values = $values->toArrayUpper();
			$key = strtoupper($key);
		}

		$id = $values[$key] ?? null;

		$query = $id > 0
			? $this->db->update($this->getTableName(), $values)->where('%n = ?', $key, $id)
			: $this->db->insert($this->getTableName(), $values);

		return $query->execute();
	}


	/**
	 * Retrieves the ID of the last inserted record.
	 *
	 * @param string|null $sequence The sequence name to use (optional).
	 * @return int The ID of the inserted record.
	 * @throws Exception If a database error occurs.
	 */
	public function getInsertId(?string $sequence = null): int
	{
		return $this->db->getInsertId($sequence);
	}
}
