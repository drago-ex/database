<?php

declare(strict_types = 1);

/**
 * Drago Database
 * Package built on Nette Framework
 */
namespace Drago\Database;

use Dibi\Connection;
use Dibi\Fluent;
use stdClass;


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
		return $this->getRecords()
			->where("{$this->primaryId} = ?", $id);
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
		return $this->db
			->delete($this->table)
			->where("{$this->primaryId} = ?", $id);
	}


	/**
	 * Insert new record.
	 * @throws Dibi\Exception
	 */
	public function add(array $args, string $sequence = null): int
	{
		$this->db->insert($this->table, $args)->execute();
		$insertId = $this->db->getInsertId($sequence);
		return $insertId;
	}
}
