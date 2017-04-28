<?php

/**
 * Extending for Nette Framework.
 * Copyright (c) 2015, Zdeněk Papučík
 */
namespace Drago\Database;
use Nette;

/**
 * Entity iterator.
 * @author Zdeněk Papučík
 */
class Iterator
{
	use Nette\StaticClass;
	
	/**
	 * Iteration items.
	 * @param  object
	 * @return array
	 */
	public static function set($entity)
	{
		$items = [];
		foreach ($entity as $key => $item) {
			$items[$key] = $item;
		}
		return $items;
	}

}
