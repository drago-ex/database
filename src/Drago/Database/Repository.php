<?php

declare(strict_types = 1);

/**
 * Drago Extension
 * Package built on Nette Framework
 */

namespace Drago\Database;

use Dibi\Connection;
use Dibi\Fluent;
use stdClass;


/**
 * Repository base.
 * @property-read  Connection|stdClass  $db
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
	 * @return \Dibi\Result|int|null
	 * @throws \Dibi\Exception
	 */
	public function discover(string $column, $args)
	{
		return $this->all()
			->where('%n = ?', $column, $args)
			->execute();
	}


	/**
	 * Find record by id. (Can only be used when a variable primaryId is set.)
	 * @return \Dibi\Result|int|null
	 * @throws \Dibi\Exception
	 */
	public function discoverId(int $id)
	{
		return $this->discover($this->primaryId, $id);
	}


	/**
	 * Delete a record by parameter.
	 * @param  int|string  $args
	 * @return \Dibi\Result|int|null
	 * @throws \Dibi\Exception
	 */
	public function erase(string $column, $args)
	{
		return $this->db
			->delete($this->table)
			->where('%n = ?', $column, $args)
			->execute();
	}


	/**
	 * Deleting an entry by the primary key.
	 * @return \Dibi\Result|int|null
	 * @throws \Dibi\Exception
	 */
	public function eraseId(int $id)
	{
		return $this->erase($this->primaryId, $id);
	}


	/**
	 * Saving a record by parameter.
	 * @param  mixed ...$args
	 * @return \Dibi\Result|int|null
	 * @throws \Dibi\Exception
	 */
	public function put(array $records, string $column = null, ...$args)
	{
		$query = $column && $args
			? $this->db->update($this->table, $records)->where('%n = ?', $column, $args)
			: $this->db->insert($this->table, $records);
		return $query->execute();
	}


	/**
	 * Saving an entry by entity.
	 * @return \Dibi\Result|int|null
	 * @throws \Dibi\Exception
	 */
	public function add(Entity $entity, int $id = null)
	{
		return isset($id)
			? $this->put($entity->getModify(), $this->primaryId, $id)
			: $this->put($entity->getModify());
	}


	/**
	 * Get the id of the inserted record.
	 * @throws \Dibi\Exception
	 */
	public function getInsertedId(string $sequence = null): int
	{
		return $this->db->getInsertId($sequence);
	}
}
