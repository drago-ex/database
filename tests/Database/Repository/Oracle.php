<?php

declare(strict_types = 1);

namespace Test\Repository;

use Drago\Database\Connect;
use Drago\Database\Repository;
use Examples\EntityConverter;


class Oracle extends Connect
{
	use Repository;

	/** @var string */
	private $table = EntityConverter::TABLE;

	/** @var int */
	private $primaryId = EntityConverter::SAMPLE_ID;

	public function test()
	{
		return $this->db->query('SELECT * FROM TEST')->fetchAll();
	}


	/**
	 * Find by id.
	 * @return array|EntityConverter|null
	 * @throws \Dibi\Exception
	 */
	public function find(int $id)
	{
		return $this->discoverId($id)
			->setRowClass(EntityConverter::class)
			->fetch();
	}


	/**
	 * Save record.
	 * @return \Dibi\Result|int|null
	 * @throws \Dibi\Exception
	 */
	public function save(EntityConverter $entity)
	{
		$id = $entity->getSampleId();
		return $this->put($entity, $id);
	}
}
