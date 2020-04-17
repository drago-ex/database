<?php

declare(strict_types = 1);

use Drago\Database;
use Examples\EntityConverter;


class Oracle extends Database\Connect
{
	use Database\Repository;

	public function table(): string
	{
		return EntityConverter::TABLE;
	}


	public function columnId(): string
	{
		return EntityConverter::SAMPLE_ID;
	}


	/**
	 * Find by id.
	 * @return array|EntityConverter|null
	 * @throws Dibi\Exception
	 */
	public function find(int $id)
	{
		return $this->discoverId($id)
			->setRowClass(EntityConverter::class)
			->fetch();
	}


	/**
	 * Save record.
	 * @return Dibi\Result|int|null
	 * @throws Dibi\Exception
	 */
	public function save(EntityConverter $entity)
	{
		$id = $entity->getSampleId();
		return $this->put($entity, $id);
	}
}
