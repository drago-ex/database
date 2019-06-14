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
	 * @param mixed ...$parm
	 */
	public function find(string $cond, ...$parm): Fluent
	{
		return $this->getRecords()
			->where("{$cond} = ?", $parm);
	}


	/**
	 * Find record by primary id.
	 */
	public function findById(int $id): Fluent
	{
		return $this->find($this->primaryId, $id);
	}


	/**
	 * Remove record by parameters.
	 * @param mixed ...$parm
	 */
	public function remove(string $cond, ...$parm): Fluent
	{
		return $this->db
			->delete($this->table)
			->where("{$cond} = ?", $parm);
	}


	/**
	 * Remove record by primary id.
	 */
	public function removeById(int $id): Fluent
	{
		return $this->remove($this->primaryId, $id);
	}


	/**
	 * Save record by parameters.
	 * @param mixed ...$parm
	 */
	public function save(array $args, string $cond = null, ...$parm): Fluent
	{
		$query = $cond && $parm
			? $this->db->update($this->table, $args)->where("{$cond} = ?", $parm)
			: $this->db->insert($this->table, $args);

		return $query;
	}


	/**
	 * Save record by id.
	 */
	public function saveById(array $args, int $id): Fluent
	{
		$query = $this->save($args, $this->primaryId, $id);
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
