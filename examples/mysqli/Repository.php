<?php

declare(strict_types = 1);

namespace Examples\Mysql;

use Drago\Database\Connect;


class Repository extends Connect
{
	use \Drago\Database\Repository;

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
	 * Find by string.
	 * @return array|Entity|null
	 * @throws \Dibi\Exception
	 */
	public function findBy(string $string)
	{
		return $this->discover(Entity::SAMPLE_STRING, $string)
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
		return $this->add($entity, $id);
	}
}
