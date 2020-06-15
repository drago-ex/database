<?php

declare(strict_types = 1);

use Dibi\Exception;
use Dibi\Result;
use Drago\Database\Connect;
use Drago\Database\Repository;
use Examples\Entity;


class Mysql extends Connect
{
	use Repository;

	/** @var string */
	public $table = Entity::TABLE;

	/** @var string */
	public $columnId = Entity::SAMPLE_ID;


	/**
	 * Find by id.
	 * @return array|Entity|null
	 * @throws Exception
	 */
	public function find(int $id)
	{
		return $this->discoverId($id)
			->setRowClass(Entity::class)
			->fetch();
	}


	/**
	 * Save record.
	 * @return Result|int|null
	 * @throws Exception
	 */
	public function save(Entity $entity)
	{
		$id = $entity->getSampleId();
		return $this->put($entity->getModify(), $id);
	}
}
