<?php

declare(strict_types = 1);

/**
 * Drago Extension
 * Package built on Nette Framework
 */

namespace Drago\Database;

use Dibi\Connection;
use Dibi\Exception;
use Dibi\Fluent;
use Dibi\Result;


/**
 * Repository base.
 * @property-read  Connection  $db
 * @property  string  $table
 * @property  string  $primary
 */
trait Repository
{
	/**
	 * Get all records.
	 */
	public function all(): Fluent
	{
		return $this->db
			->select('*')
			->from($this->table);
	}


	/**
	 * Find a record by parameter.
	 */
	public function discover(string $column, int|string $args): Fluent
	{
		return $this->all()
			->where("{$column} = ?", $args);
	}


	/**
	 * Find record by id.
	 */
	public function get(int $id): Fluent
	{
		return $this->discover($this->primary, $id);
	}


	/**
	 * Deleting an records by the primary key.
	 * @throws Exception
	 */
	public function erase(int $id): Result|int|null
	{
		return $this->db
			->delete($this->table)
			->where("{$this->primary} = ?", $id)
			->execute();
	}


	/**
	 * Saving an records.
	 * @throws Exception
	 */
	public function put(array $data): Result|int|null
	{
		$id = $data[$this->primary] ?? null;
		$result = $id > 0
			? $this->db->update($this->table, $data)->where("{$this->primary} = ?", $id)
			: $this->db->insert($this->table, $data);
		return $result->execute();
	}


	/**
	 * Get the id of the inserted record.
	 * @throws Exception
	 */
	public function getInsertId(string $sequence = null): int
	{
		return $this->db->getInsertId($sequence);
	}
}
