<?php

/**
 * Extending for Nette Framework
 * Copyright (c) 2015, Zdeněk Papučík
 */
namespace Drago\Database;
use Nette;

/**
 * Entity iterator.
 */
class Iterator
{
	use Nette\StaticClass;

	/**
	 * Iteration items.
	 * @param  mixed
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
