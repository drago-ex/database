<?php

declare(strict_types = 1);

use Dibi\Exception;
use Dibi\Result;
use Drago\Database\Connect;
use Drago\Database\Repository;
use Examples\EntityConverter;
use Examples\FormData;


class Oracle extends Connect
{
	use Repository;

	/** @var string */
	public $table = EntityConverter::TABLE;

	/** @var string */
	public $columnId = EntityConverter::SAMPLE_ID;


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
	 * Save record.
	 * @return Result|int|null
	 * @throws Exception
	 */
	public function saveEntity(EntityConverter $entity)
	{
		$id = $entity->getSampleId();
		return $this->put($entity->getModify(), $id);
	}


	/**
	 * Saving an records by form data.
	 * @return Result|int|null
	 * @throws Exception
	 */
	public function saveFormData(FormData $data)
	{
		$record = (array) $data;
		$dataConverted = new EntityConverter($record);
		return $this->put($dataConverted->toArrayUpper(), $data[$this->columnId] ?? null);
	}
}
