<?php

/**
 * This file is part of the Drago Framework
 * Copyright (c) 2015, Zdeněk Papučík
 */
namespace Drago\Database;
use Nette;

/**
 * Database entity.
 * @author Zdeněk Papučík
 */
abstract class Entity extends Nette\Object
{
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
