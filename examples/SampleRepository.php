<?php

declare(strict_types = 1);

namespace Examples;

use Drago\Database\Connect;
use Drago\Database\Repository;


class SampleRepository extends Connect
{
	use Repository;

	/** @var string */
	private $table = SampleEntity::TABLE;

	/** @var int */
	private $primaryId = SampleEntity::SAMPLE_ID;


	/**
	 * Find by id.
	 * @return array|SampleEntity|null
	 * @throws \Dibi\Exception
	 */
	public function find(int $id)
	{
		return $this->discoverId($id)
			->setRowClass(SampleEntity::class)
			->fetch();
	}


	/**
	 * Find by string.
	 * @return array|SampleEntity|null
	 * @throws \Dibi\Exception
	 */
	public function findBy(string $string)
	{
		return $this->discover(SampleEntity::SAMPLE_STRING, $string)
			->setRowClass(SampleEntity::class)
			->fetch();
	}


	/**
	 * Save record.
	 * @return \Dibi\Result|int|null
	 * @throws \Dibi\Exception
	 */
	public function save(SampleEntity $entity)
	{
		$id = $entity->getSampleId();
		return $this->add($entity, $id);
	}
}
