<?php

/**
 * Extension Nette
 * Copyright (c) 2015, Zdeněk Papučík
 */
namespace Drago\Database;
use Nette;

/**
 * Iterátor entit.
 */
class Iterator
{
	use Nette\StaticClass;

	/**
	 * @param mixed
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
