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
use Drago\Attr\Attributes;


/**
 * Repository base.
 * @property-read  Connection  $db
 * @property-read  Attributes  $attributes
 */
trait Repository
{
	/**
	 * Table name.
	 */
	public function getTable(): string
	{
		if (!isset($this->attributes()[0])) {
			throw new \Exception('In the repository ' . static::class . ' you do not have a table name in the Table attribute.');
		}
		return $this->attributes()[0];
	}


	/**
	 * Table primary key.
	 */
	public function getPrimary(): string
	{
		if (!isset($this->attributes()[1])) {
			throw new \Exception('In the repository ' . static::class . ' you do not have the primary key of the table in the Table attribute.');
		}
		return $this->attributes()[1];
	}


	/**
	 * Get all records.
	 */
	public function all(): Fluent
	{
		return $this->db
			->select('*')
			->from($this->getTable());
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
		return $this->discover($this->getPrimary(), $id);
	}


	/**
	 * Deleting an records by the primary key.
	 * @throws Exception
	 */
	public function erase(int $id): Result|int|null
	{
		return $this->db
			->delete($this->getTable())
			->where("{$this->getPrimary()} = ?", $id)
			->execute();
	}


	/**
	 * Saving an records.
	 * @throws Exception
	 */
	public function put(array $data): Result|int|null
	{
		$id = $data[$this->getPrimary()] ?? null;
		return $id > 0
			? $this->db->update($this->getTable(), $data)->where("{$this->getPrimary()} = ?", $id)->execute()
			: $this->db->insert($this->getTable(), $data)->execute();
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
