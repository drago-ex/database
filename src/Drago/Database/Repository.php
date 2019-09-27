<?php

declare(strict_types = 1);

/**
 * Drago Database
 * Package built on Nette Framework
 */
namespace Drago\Database;

use Dibi;
use Dibi\Connection;
use Dibi\Fluent;
use Dibi\Result;
use stdClass;


/**
 * Repository base.
 * @property-read  Connection|stdClass  $db
 * @package Drago\Database
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
	 * @return Result|int|null
	 * @throws Dibi\Exception
	 */
	public function discover(string $column, int $id)
	{
		return $this->all()
			->where("{$column} = ?", $id)
			->execute();
	}


	/**
	 * Find record by id. (Can only be used when a variable primaryId is set.)
	 * @return Result|int|null
	 * @throws Dibi\Exception
	 */
	public function discoverId(int $id)
	{
		return $this->discover($this->primaryId, $id);
	}


	/**
	 * Delete a record by parameter.
	 * @return Result|int|null
	 * @throws Dibi\Exception
	 */
	public function erase(string $column, int $id)
	{
		return $this->db
			->delete($this->table)
			->where("{$column} = ?", $id)
			->execute();
	}


	/**
	 * Deleting an entry by the primary key.
	 * @return Result|int|null
	 * @throws Dibi\Exception
	 */
	public function eraseId(int $id)
	{
		return $this->delete($this->primaryId, $id);
	}


	/**
	 * Saving a record by parameter.
	 * @return Result|int|null
	 * @throws Dibi\Exception
	 */
	public function put(array $records, string $column = null, int $id = null)
	{
		$query = $column && $id
			? $this->db->update($this->table, $records)->where("{$column} = ?", $id)
			: $this->db->insert($this->table, $records);
		return $query->execute();
	}


	/**
	 * Saving an entry by entity.
	 * @return Result|int|null
	 * @throws Dibi\Exception
	 */
	public function add(Entity $entity, int $id = null)
	{
		$query = $id
			? $this->put($entity->getModify(), $this->primaryId, $id)
			: $this->put($entity->getModify());
		return $query;
	}


	/**
	 * Get the id of the inserted record.
	 * @throws Dibi\Exception
	 */
	public function getInsertedId(string $sequence = null): int
	{
		return $this->db->getInsertId();
	}
}
