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
	 * Get all records.
	 * @throws AttributeDetectionException
	 */
	public function all(): Fluent
	{
		return $this->db
			->select('*')
			->from($this->getTable());
	}


	/**
	 * Find a record by parameter.
	 * @throws AttributeDetectionException
	 */
	public function discover(string $column, int|string $args): Fluent
	{
		return $this->all()
			->where("$column = ?", $args);
	}


	/**
	 * Find record by id.
	 * @throws AttributeDetectionException
	 */
	public function get(int $id): Fluent
	{
		return $this->discover($this->getId(), $id);
	}


	/**
	 * Deleting a records by the primary key.
	 * @throws Exception
	 * @throws AttributeDetectionException
	 */
	public function remove(int $id): Result|int|null
	{
		return $this->db
			->delete($this->getTable())
			->where("{$this->getId()} = ?", $id)
			->execute();
	}


	/**
	 * Saving a records.
	 * @throws Exception
	 * @throws AttributeDetectionException
	 */
	public function put(array $data): Result|int|null
	{
		$id = $data[$this->getId()] ?? null;
		$query = $id > 0
			? $this->db->update($this->getTable(), $data)->where("{$this->getId()} = ?", $id)
			: $this->db->insert($this->getTable(), $data);
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
