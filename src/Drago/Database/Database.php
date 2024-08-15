<?php

declare(strict_types=1);

namespace Drago\Database;

use Dibi\Connection;
use Dibi\Exception;
use Dibi\Result;
use Drago\Attr\AttributeDetection;
use Drago\Attr\AttributeDetectionException;
use Drago\Database\Entity;
use Drago\Database\EntityOracle;


/**
 * @template T
 */
abstract class Database
{
	use AttributeDetection;

	public function __construct(
		protected Connection $db,
	) {
	}


	/**
	 * Database connection.
	 */
	public function getConnection(): Connection
	{
		return $this->db;
	}


	/**
	 * @return FluentExtra<T>
	 */
	public function command(): FluentExtra
	{
		return new FluentExtra($this);
	}


	/**
	 * @return FluentExtra<T>
	 * @throws AttributeDetectionException
	 */
	public function read(...$args): FluentExtra
	{
		$command = $this->command();
		$command = $args ? $command->select(...$args) : $command->select('*');
		return $command->from($this->getTableName());
	}


    /**
     * @return FluentExtra<T>
     * @throws AttributeDetectionException
     */
	public function find(string $column, int|string $args): FluentExtra
	{
		return $this->command()->select('*')
			->from($this->getTableName())
			->where('%n = ?', $column, $args);
	}


	/**
	 * @throws AttributeDetectionException
	 */
	public function delete(int $id = null): FluentExtra
	{
		$command = $this->command()->delete()
			->from($this->getTableName());

		if ($id > 0) {
			$command->where('%n = ?', $this->getPrimaryKey(), $id);
		}

		return $command;
	}


	/**
	 * @throws AttributeDetectionException
	 * @throws Exception
	 */
	public function save(mixed $values): Result|int|null
	{
		$key = $this->getPrimaryKey();
		if ($values instanceof Entity) {
			$values = $values->toArray();

		} elseif ($values instanceof EntityOracle) {
			$values = $values->toArrayUpper();
			$key = strtoupper($key);
		}

		$id = $values[$key] ?? null;
		$query = $id > 0
			? $this->getConnection()->update($this->getTableName(), $values)->where('%n = ?', $key, $id)
			: $this->getConnection()->insert($this->getTableName(), $values);
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
