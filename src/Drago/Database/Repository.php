<?php

declare(strict_types = 1);

/**
 * Drago Database
 * @copyright Zdeněk Papučík
 */
namespace App\Presenters;

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
	public function get(): Fluent
	{
		return $this->db
			->select('*')
			->from($this->table);
	}


	/**
	 * Find record.
	 */
	public function find(int $id): Fluent
	{
		return $this->get()
			->where("{$this->primaryId} = ?", $id);
	}


	/**
	 * Remove record.
	 */
	public function remove(int $id): Fluent
	{
		return $this->db
			->delete($this->table)
			->where("{$this->primaryId} = ?", $id);
	}


	/**
	 * Insert or update record.
	 */
	public function save(array $args, int $id = null): Fluent
	{
		$update = $this->db
			->update($this->table, $args)
			->where("{$this->primaryId} = ?", $id);

		$insert = $this->db->insert($this->table, $args);
		$id ? $row = $update : $row = $insert;
		return $row;
	}
}
