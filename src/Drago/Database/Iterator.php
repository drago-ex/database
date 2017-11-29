<?php

/**
 * Drago Database
 * Copyright (c) 2015, Zdeněk Papučík
 */
namespace Drago\Database;

use Nette;
use Nette\Utils;

/**
 * Iterator entity or convert lower/upper keys.
 */
class Iterator
{
	use Nette\StaticClass;

    	/**
	 * Convert keys to lower.
	 * @var string
	 */
	const LOWER = 'lower';

	/**
	 * Convert keys to upper.
	 * @var string
	 */
	const UPPER = 'upper';

	/**
	 * Convert entity and keys.
	 * @param mixed
	 * @param string
	 * @return array
	 */
	public static function set($entity, $convert = null)
	{
		$items = [];
		foreach ($entity as $key => $item) {
			switch ($convert) {
				case 'lower': $items[$convert ? Utils\Strings::lower($key) : $key] = $item; break;
				case 'upper': $items[$convert ? Utils\Strings::upper($key) : $key] = $item; break;
				default:
					$items[$key] = $item;
				break;
			}

		}
		return array_filter($items);
	}

}
