<?php

declare(strict_types = 1);

use Dibi\Exception;
use Dibi\Result;
use Drago\Database\Connect;
use Drago\Database\Repository;
use Examples\Entity;
use Examples\FormData;


class Mysql extends Connect
{
	use Repository;

	/** @var string */
	public $table = Entity::TABLE;

	/** @var string */
	public $columnId = Entity::SAMPLE_ID;


	/**
	 * Get all records.
	 * @return array|array[]|Entity[]
	 * @throws Exception
	 */
	public function getAll()
	{
		return $this->all()
			->execute()
			->setRowClass(Entity::class)
			->fetchAll();
	}


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
	 * Save record by entity.
	 * @return Result|int|null
	 * @throws Exception
	 */
	public function saveEntity(Entity $entity)
	{
		return $this->put($entity->getModify(), $entity->getSampleId());
	}


	/**
	 * Saving an records by form data.
	 * @return Result|int|null
	 * @throws Exception
	 */
	public function saveFormData(FormData $data)
	{
		if (!$data->sampleId) {
			unset($data->sampleId);
		}
		$data = (array) $data;
		return $this->put($data, $data->sampleId ?? null);
	}
}
