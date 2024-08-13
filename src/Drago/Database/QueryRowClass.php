<?php

declare(strict_types=1);

/**
 * Drago Extension
 * Package built on Nette Framework
 */

namespace Drago\Database;

use Dibi\Exception;
use Dibi\Fluent;
use Drago\Attr\AttributeDetectionException;


/**
 * Repository base.
 * @template T
 */
trait QueryRowClass
{
	use QueryTable;

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
