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
			->from($this->getDatabaseTable());

		if ($column && $args) {
			$query->where("$column = ?", $args);
		}

		return $query;
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
			->delete($this->getDatabaseTable())
			->where("{$this->getPrimaryKey()} = ?", $id)
			->execute();
	}


	/**
	 * Saving a records.
	 * @throws Exception
	 * @throws AttributeDetectionException
	 */
	public function put(mixed $data): Result|int|null
	{
		$primaryKey = $this->getPrimaryKey();
		if ($data instanceof Entity) {
			$data = $data->toArray();

		} elseif ($data instanceof EntityOracle) {
			$data = $data->toArrayUpper();
			$primaryKey = strtoupper($primaryKey);
		}

		$id = $data[$primaryKey] ?? null;
		$query = $id > 0
			? $this->db->update($this->getDatabaseTable(), $data)->where("$primaryKey = ?", $id)
			: $this->db->insert($this->getDatabaseTable(), $data);
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
