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
 * @template T
 */
class FluentExtra extends Fluent
{
	public function __construct(
		private readonly Database $db,
	) {
		parent::__construct($db->getConnection());
	}


	public function where(...$cond): self
	{
		parent::where(...$cond);
		return $this;
	}


	public function and(...$cond): self
	{
		parent::and(...$cond);
		return $this;
	}


	public function or(...$cond): self
	{
		parent::or(...$cond);
		return $this;
	}


	public function orderBy(...$field): self
	{
		parent::orderBy(...$field);
		return $this;
	}


	/**
	 * @return T
	 * @throws Exception
	 * @throws AttributeDetectionException
	 */
	public function record(): mixed
	{
		return $this->execute()
			->setRowClass($this->db->getClassName())
			->fetch();
	}


	/**
	 * @return T[]
	 * @throws Exception
	 * @throws AttributeDetectionException
	 */
	public function recordAll(?int $offset = null, ?int $limit = null): array
	{
		return $this->execute()
			->setRowClass($this->db->getClassName())
			->fetchAll($offset, $limit);
	}
}
