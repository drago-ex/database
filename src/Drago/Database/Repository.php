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
use Entity\UserEntity;
use stdClass;
use Tracy\Debugger;


/**
 * Repository base.
 * @property-read Connection|stdClass $db
 * @package Drago\Database
 */
trait Repository
{
	/**
	 * Get all records.
	 */
	public function getRecords(): Fluent
	{
		return $this->db
			->select('*')
			->from($this->table);
	}


	/**
	 * Find a record by parameters.
	 * @return Result|int|null
	 * @throws Dibi\Exception
	 */
	public function find(string $cond, ...$parm)
	{
		return $this->getRecords()
			->where("{$cond} = ?", $parm)
			->execute();
	}


	/**
	 * Find record by id.
	 * @return Result|int|null
	 * @throws Dibi\Exception
	 */
	public function findById(int $id)
	{
		return $this->find($this->primaryId, $id);
	}


	/**
	 * Remove record by parameters.
	 * @param  mixed ...$parm
	 * @return Result|int|null
	 * @throws Dibi\Exception
	 */
	public function remove(string $cond, ...$parm)
	{
		return $this->db
			->delete($this->table)
			->where("{$cond} = ?", $parm)
			->execute();
	}


	/**
	 * Remove record by primary id.
	 * @return Result|int|null
	 * @throws Dibi\Exception
	 */
	public function removeById(int $id)
	{
		return $this->remove($this->primaryId, $id);
	}


	/**
	 * Save record by parameters.
	 * @param mixed ...$parm
	 * @return Result|int|null
	 * @throws Dibi\Exception
	 */
	public function save(array $args, string $cond = null, ...$parm)
	{
		$query = $cond && $parm
			? $this->db->update($this->table, $args)->where("{$cond} = ?", $parm)
			: $this->db->insert($this->table, $args);
		return $query->execute();
	}


	/**
	 * Save record by entity.
	 * @return Result|int|null
	 * @throws Dibi\Exception
	 */
	public function saveRecord(Entity $entity, int $id = null)
	{
		$query = $id
			? $this->save($entity->getModify(), $this->primaryId, $id)
			: $this->save($entity->getModify());
		return $query;
	}


	/**
	 * Returns the ID of the inserted record.
	 * @throws Dibi\Exception
	 */
	public function getInsertId(string $sequence = null): int
	{
		return $this->db->getInsertId();
	}
}
