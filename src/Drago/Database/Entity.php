<?php

/**
 * Extending for Nette Framework.
 * Copyright (c) 2015, Zdeněk Papučík
 *
 * @package Drago
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
	
	/**
	 * Iteration items.
	 * @param  object
	 * @return array
	 */
	public function iterator($entity)
	{
		$items = [];
		foreach ($entity as $key => $item) {
			$items[$key] = $item;
		}
		return $items;
	}

}
