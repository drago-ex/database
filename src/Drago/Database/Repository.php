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
use stdClass;


/**
 * Repository base.
 * @property-read  Connection|stdClass  $db
 * @property  string  $table
 * @property  string  $columnId
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
	 * @param  int|string  $args
	 * @return Result|int|null
	 * @throws Exception
	 */
	public function discover(string $column, $args)
	{
		return $this->all()
			->where("{$column} = ?", $args)
			->execute();
	}


	/**
	 * Find record by id (Can only be used when a variable primaryId is set.)
	 * @return Result|int|null
	 * @throws Exception
	 */
	public function discoverId(int $id)
	{
		return $this->discover($this->columnId, $id);
	}


	/**
	 * Deleting an records by the primary key.
	 * @return Result|int|null
	 * @throws Exception
	 */
	public function eraseId(int $id)
	{
		return $this->db
			->delete($this->table)
			->where("{$this->columnId} = ?", $id)
			->execute();
	}


	/**
	 * Saving an records by array.
	 * @return Result|int|null
	 * @throws Exception
	 */
	public function put(array $data)
	{
		$id = $data[$this->columnId] ?? null;
		return $id > 0
			? $this->db->update($this->table, $data)->where("{$this->columnId} = ?", $id)->execute()
			: $this->db->insert($this->table, $data)->execute();
	}


	/**
	 * Get the id of the inserted record.
	 * @throws Exception
	 */
	public function getInsertedId(string $sequence = null): int
	{
		return $this->db->getInsertId($sequence);
	}
}
