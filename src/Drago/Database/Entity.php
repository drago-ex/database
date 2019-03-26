<?php declare(strict_types = 1);

/**
 * Drago Database
 * Copyright (c) 2015, Zdeněk Papučík
 */
namespace Drago\Database;
use Nette;

/**
 * Base entity.
 */
abstract class Entity
{
	use Nette\SmartObject;

	/**
	 * @var int
	 */
	private $id;


	public function setId(int $id)
	{
		$this->id = $id;
	}


	/**
	 * Get the record ID.
	 */
	public function getId(): int
	{
		return $this->id;
	}
}
