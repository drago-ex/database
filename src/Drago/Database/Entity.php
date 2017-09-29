<?php

/**
 * Extension Nette
 * Copyright (c) 2015, Zdeněk Papučík
 */
namespace Drago\Database;
use Nette;

/**
 * Základní entita.
 */
abstract class Entity
{
	use Nette\SmartObject;

	/**
	 * @var int
	 */
	private $id;

	/**
	 * @param int
	 */
	public function setId($id)
	{
		$this->id = $id;
	}

	/**
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}

}
