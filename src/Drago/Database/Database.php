<?php

declare(strict_types=1);

namespace App\src\Drago\Database;

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
	 * @throws AttributeDetectionException
	 */
	public function delete(): FluentExtra
	{
		return $this->command()->delete()
			->from($this->getTableName());
	}


	/**
	 * @throws AttributeDetectionException
	 */
	public function deleteById(int $id): FluentExtra
	{
		return $this->delete()
			->where('%n = ?', $this->getPrimaryKey(), $id);
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
}
