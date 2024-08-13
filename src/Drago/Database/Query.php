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
 * @property-read Connection $db
 */
trait Query
{
	use AttributeDetection;

	/**
	 * Get records from table.
	 * @throws AttributeDetectionException
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
	 * Find record by id.
	 * @throws AttributeDetectionException
	 */
	public function get(int $id): Fluent
	{
		return $this->table('%n = ?', $this->getPrimaryKey(), $id);
	}


	/**
	 * Deleting a records.
	 * @throws AttributeDetectionException
	 */
	public function remove(int|array ...$cond): Fluent
	{
		$delete = $this->db->delete($this->getTableName());
		if (is_int($cond)) $delete->where('%n = ?', $this->getPrimaryKey(), $cond); else {
			$delete->where(...$cond);
		}
		return $delete;
	}


	/**
	 * Saving a records.
	 * @throws Exception
	 * @throws AttributeDetectionException
	 */
	public function put(mixed $values): Result|int|null
	{
		$key = $this->getPrimaryKey();
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
	 * Get the id of the inserted record.
	 * @throws Exception
	 */
	public function getInsertId(?string $sequence = null): int
	{
		return $this->db->getInsertId($sequence);
	}
}
