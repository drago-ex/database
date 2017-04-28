<?php

/**
 * Extending for Nette Framework.
 * Copyright (c) 2015, Zdeněk Papučík
 */
namespace Drago\Database;
use Nette;

/**
 * Database entity.
 * @author Zdeněk Papučík
 */
abstract class Entity
{
	use Nette\SmartObject;

	/**
	 * @var int
	 */
	private $id;

	/**
	 * Setter.
	 * @param int
	 */
	public function setId($id)
	{
		$this->id = $id;
	}

	/**
	 * Getter.
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}

}
