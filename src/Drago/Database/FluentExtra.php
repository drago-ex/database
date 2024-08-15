<?php

declare(strict_types=1);

namespace App\src\Drago\Database;

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


	public function where(...$cond): FluentExtra
	{
		parent::where(...$cond);
		return $this;
	}


	public function and(...$cond): FluentExtra
	{
		parent::and(...$cond);
		return $this;
	}


	public function or(...$cond): FluentExtra
	{
		parent::or(...$cond);
		return $this;
	}


	/**
	 * Find record by id.
	 * @throws AttributeDetectionException
	 */
	public function findById(int $id): FluentExtra
	{
		$key = $this->db->getPrimaryKey();
		$this->where('%n = ?', $key, $id);
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
