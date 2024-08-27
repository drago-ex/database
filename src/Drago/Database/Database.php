<?php

declare(strict_types=1);

/**
 * Drago Extension
 * Package built on Nette Framework
 */

namespace Drago\Database;

use Dibi\Connection;
use Dibi\Exception;
use Dibi\Result;
use Drago\Attr\AttributeDetection;
use Drago\Attr\AttributeDetectionException;


/**
 * @template T
 * @property-read Connection $connection
 */
trait Database
{
	use AttributeDetection;

	/**
	 * Database connection.
	 */
	public function getConnection(): Connection
	{
		return $this->connection;
	}


	/**
	 * @return ExtraFluent<T>
	 * @throws AttributeDetectionException
	 */
	public function command(): ExtraFluent
	{
		$fluent = new ExtraFluent($this->getConnection());
		$fluent->className = $this->getClassName();
		return $fluent;
	}


	/**
	 * Reading records from table.
	 * @return ExtraFluent<T>
	 * @throws AttributeDetectionException
	 */
	public function read(...$args): ExtraFluent
	{
		return $this->command()
			->select(...$args)
			->from($this->getTableName());
	}


	/**
	 * Find records by column name.
	 * @return ExtraFluent<T>
	 * @throws AttributeDetectionException
	 */
	public function find(string $column, int|string $args): ExtraFluent
	{
		return $this->read('*')
			->where('%n = ?', $column, $args);
	}


	/**
	 * Get record by id (if a primary key is available).
	 * @return ExtraFluent<T>
	 * @throws AttributeDetectionException
	 */
	public function get(int $id): ExtraFluent
	{
		return $this->read('*')
			->where('%n = ?', $this->getPrimaryKey(), $id);
	}


	/**
	 * Delete record by column name.
	 * @throws AttributeDetectionException
	 */
	public function delete(string $column, int|string $args): ExtraFluent
	{
		return $this->command()
			->delete()
			->from($this->getTableName())
			->where('%n = ?', $column, $args);
	}


	/**
	 * Insert or update.
	 * @throws AttributeDetectionException
	 * @throws Exception
	 */
	public function save(mixed $values): Result|int|null
	{
		$key = $this->getPrimaryKey();
		$table = $this->getTableName();
		if ($values instanceof Entity) {
			$values = $values->toArray();

		} elseif ($values instanceof EntityOracle) {
			$values = $values->toArrayUpper();
			$key = strtoupper($key);
		}

		$id = $values[$key] ?? null;
		$query = $id > 0
			? $this->getConnection()->update($table, $values)->where('%n = ?', $key, $id)
			: $this->getConnection()->insert($table, $values);
		return $query->execute();
	}


	/**
	 * Get the id of the inserted record.
	 * @throws Exception
	 */
	public function getInsertId(?string $sequence = null): int
	{
		return $this->getConnection()
			->getInsertId($sequence);
	}
}
