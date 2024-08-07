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
	 * Get record from table.
	 * @return T
	 * @throws AttributeDetectionException
	 * @throws Exception
	 */
	public function find...$cond): mixed
	{
		return $this->fetch($this->table($cond));
	}


	/**
	 * Get record by id.
	 * @return T
	 * @throws AttributeDetectionException
	 * @throws Exception
	 */
	public function one(int $id): mixed
	{
		return $this->fetch($this->get($id));
	}


	/**
	 * Get all records from table.
	 * @return T[]
	 * @throws Exception
	 * @throws AttributeDetectionException
	 */
	public function all(?int $offset = null, ?int $limit = null): array
	{
		return $this->fetchAll($this->table(), $offset, $limit);
	}


	/**
	 * @return T
	 * @throws Exception
	 * @throws AttributeDetectionException
	 */
	public function fetch(Fluent $fluent): mixed
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
	public function fetchAll(Fluent $fluent, ?int $offset = null, ?int $limit = null): array
	{
		return $fluent->execute()
			->setRowClass($this->getClassName())
			->fetchAll($offset, $limit);
	}
}
