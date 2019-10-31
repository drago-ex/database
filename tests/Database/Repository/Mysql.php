<?php

declare(strict_types = 1);

namespace Test\Repository;

use Drago\Database\Connect;
use Drago\Database\Repository;
use Examples\Entity;


class Mysql extends Connect
{
	use Repository;

	/** @var string */
	private $table = Entity::TABLE;

	/** @var int */
	private $primaryId = Entity::SAMPLE_ID;


	/**
	 * Find by id.
	 * @return array|Entity|null
	 * @throws \Dibi\Exception
	 */
	public function find(int $id)
	{
		return $this->discoverId($id)
			->setRowClass(Entity::class)
			->fetch();
	}


	/**
	 * Save record.
	 * @return \Dibi\Result|int|null
	 * @throws \Dibi\Exception
	 */
	public function save(Entity $entity)
	{
		$id = $entity->getSampleId();
		return $this->put($entity, $id);
	}
}
