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

	/** @var string */
	public $table = EntityConverter::TABLE;

	/** @var string */
	public $columnId = EntityConverter::SAMPLE_ID;


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
	public function save(EntityConverter $entity)
	{
		$id = $entity->getSampleId();
		return $this->put($entity->getModify(), $id);
	}
}
