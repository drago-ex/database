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
 * @property-read Connection $db
 */
trait Repository
{
	use AttributeDetection;

	/**
	 * Get records from table.
	 * @throws AttributeDetectionException
	 */
	public function table(?string $column = null, ...$args): Fluent
	{
		$query = $this->db
			->select('*')
			->from($this->getTableName());

		if ($column && $args) {
			$query->where("$column = ?", $args);
		}

		return $query;
	}


	/**
	 * Get records by table name.
	 */
	public function of(string $table, ...$args): Fluent
	{
		return $this->db
			->select('*')
			->from($table, $args);
	}


	/**
	 * Find record by id.
	 * @throws AttributeDetectionException
	 */
	public function get(int $id): Fluent
	{
		return $this->table($this->getPrimaryKey(), $id);
	}


	/**
	 * Deleting a records by the primary key.
	 * @throws Exception
	 * @throws AttributeDetectionException
	 */
	public function remove(int $id): Result|int|null
	{
		return $this->db
			->delete($this->getTableName())
			->where("{$this->getPrimaryKey()} = ?", $id)
			->execute();
	}


	/**
	 * Saving a records.
	 * @throws Exception
	 * @throws AttributeDetectionException
	 */
	public function put(array $data): Result|int|null
	{
		$id = $data[$this->getPrimaryKey()] ?? null;
		$query = $id > 0
			? $this->db->update($this->getTableName(), $data)->where("{$this->getPrimaryKey()} = ?", $id)
			: $this->db->insert($this->getTableName(), $data);
		return $query->execute();
	}


	/**
	 * Get the id of the inserted record.
	 * @throws Exception
	 */
	public function getInsertId(?string $sequence = null): int
	{
		return $this->db->getInsertId($sequence);
	}
}
