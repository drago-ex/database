<?php

declare(strict_types = 1);

/**
 * Drago Extension
 * Package built on Nette Framework
 */

namespace Drago\Database;

use Dibi;
use stdClass;


/**
 * Repository base.
 * @property-read  Dibi\Connection|stdClass  $db
 */
trait Repository
{
	/**
	 * Get all records.
	 */
	public function all(): Dibi\Fluent
	{
		return $this->db
			->select('*')
			->from($this->table);
	}


	/**
	 * Find a record by parameter.
	 * @param  int|string  $args
	 * @return Dibi\Result|int|null
	 * @throws Dibi\Exception
	 */
	public function discover(string $column, $args)
	{
		return $this->all()
			->where("{$column} = ?", $args)
			->execute();
	}


	/**
	 * Find record by id (Can only be used when a variable primaryId is set.)
	 * @return Dibi\Result|int|null
	 * @throws Dibi\Exception
	 */
	public function discoverId(int $id)
	{
		return $this->discover($this->columnId, $id);
	}


	/**
	 * Deleting an records by the primary key.
	 * @return Dibi\Result|int|null
	 * @throws Dibi\Exception
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
	 * @return Dibi\Result|int|null
	 * @throws Dibi\Exception
	 */
	public function put(array $data, int $id = null)
	{
		$query = $id > 0
			? $this->db->update($this->table, $data)->where("{$this->columnId} = ?", $id)
			: $this->db->insert($this->table, $data);
		return $query->execute();
	}


	/**
	 * Saving an records by entity.
	 * @return Dibi\Result|int|null
	 * @throws Dibi\Exception
	 */
	public function saveEntity(Entity $entity)
	{
		return $this->put($entity->getModify(), $entity->{$this->columnId});
	}


	/**
	 * Saving an records by array.
	 * @return Dibi\Result|int|null
	 * @throws Dibi\Exception
	 */
	public function saveValues(array $data)
	{
		return $this->put($data, (int) $data[$this->columnId]);
	}


	/**
	 * Get the id of the inserted record.
	 * @throws Dibi\Exception
	 */
	public function getInsertedId(string $sequence = null): int
	{
		return $this->db->getInsertId($sequence);
	}
}
