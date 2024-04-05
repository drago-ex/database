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
use Drago\Attr\AttributeDetectionException;


/**
 * Repository base.
 * @template T
 * @property-read Connection $db
 */
trait Records
{
	use Query;

	/**
	 * Get records from table.
	 * @return T
	 * @throws AttributeDetectionException
	 * @throws Exception
	 */
	public function one(...$cond): mixed
	{
		return $this->fetch($this->table(...$cond));
	}


	/**
	 * Get all records from table.
	 * @return T[]
	 * @throws Exception
	 * @throws AttributeDetectionException
	 */
	public function all(?int $offset = null, ?int $limit = null, ...$cond): array
	{
		return $this->fetchAll($this->table(...$cond), $offset, $limit);
	}


	/**
	 * Find record by id.
	 * @return T
	 * @throws AttributeDetectionException
	 * @throws Exception
	 */
	public function find(int $id): mixed
	{
		return $this->fetch($this->get($id));
	}


	/**
	 * @return T
	 * @throws Exception
	 * @throws AttributeDetectionException
	 */
	protected function fetch(Fluent $fluent): mixed
	{
		return $fluent->execute()
			->setRowClass($this->getClassName())
			->fetch();
	}


	/**
	 * @return T[]
	 * @throws Exception
	 * @throws AttributeDetectionException
	 */
	protected function fetchAll(Fluent $fluent, ?int $offset = null, ?int $limit = null): array
	{
		return $fluent->execute()
			->setRowClass($this->getClassName())
			->fetchAll($offset, $limit);
	}
}
