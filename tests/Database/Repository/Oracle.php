<?php

declare(strict_types = 1);

use Dibi\Exception;
use Dibi\Result;
use Drago\Database\Connect;
use Drago\Database\Repository;
use Examples\EntityConverter;


class Oracle extends Connect
{
	use Repository;

	public string $table = EntityConverter::TABLE;
	public string $columnId = EntityConverter::SAMPLE_ID;


	/**
	 * Get all records.
	 * @return array|array[]|EntityConverter[]
	 * @throws Exception
	 */
	public function getAll()
	{
		return $this->all()
			->execute()
			->setRowClass(EntityConverter::class)
			->fetchAll();
	}


	/**
	 * Find by id.
	 * @return array|EntityConverter|null
	 * @throws Exception
	 */
	public function find(int $id)
	{
		return $this->discoverId($id)
			->setRowClass(EntityConverter::class)
			->fetch();
	}


	/**
	 * Save record by entity.
	 * @return Result|int|null
	 * @throws Exception
	 */
	public function save(EntityConverter $entity)
	{
		return $this->put($entity->getModify(), $entity->sample_id);
	}
}
