<?php

declare(strict_types = 1);

use Drago\Database;
use Examples\Entity;


class Mysql extends Database\Connect implements Database\IRepo
{
	use Database\Repository;

	public function setTable(): void
	{
		$this->table = Entity::TABLE;
	}


	public function setColumnId(): void
	{
		$this->columnId = Entity::SAMPLE_ID;
	}


	/**
	 * Find by id.
	 * @return array|Entity|null
	 * @throws Dibi\Exception
	 */
	public function find(int $id)
	{
		return $this->discoverId($id)
			->setRowClass(Entity::class)
			->fetch();
	}


	/**
	 * Save record.
	 * @return Dibi\Result|int|null
	 * @throws Dibi\Exception
	 */
	public function save(Entity $entity)
	{
		$id = $entity->getSampleId();
		return $this->put($entity, $id);
	}
}
