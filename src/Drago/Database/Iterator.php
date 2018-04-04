<?php

/**
 * Drago Database
 * Copyright (c) 2015, Zdeněk Papučík
 */
namespace Drago\Database;

use Nette;
use Nette\Utils\Strings;
use Nette\Utils\ArrayHash;

/**
 * Basic iterator and convert to lower or upper keys.
 */
class Iterator
{
	use Nette\StaticClass;

	/**
	 * @var string
	 */
	const LOWER = 'lower';

	/**
	 * @var string
	 */
	const UPPER = 'upper';

	/**
	 * @param mixed
	 * @param string|null
	 * @return array
	 */
	public static function convert($entity, $convert = null)
	{
		$items = [];
		foreach ($entity as $key => $item) {
			switch ($convert) {
				case 'lower': $items[Strings::lower($key)] = $item; break;
				case 'upper': $items[Strings::upper($key)] = $item; break;
				default:
					$items[$key] = $item;
				break;
			}
		}
		return $items;
	}

	/**
	 * Standart convert entity to array.
	 * @param  mixed
	 * @return array
	 */
	public static function standart($entity)
	{
		return Iterator::convert($entity);
	}

	/**
	 * Covert array keys from upper to lower.
	 * @param  mixed
	 * @return array
	 */
	public static function lower($entity)
	{
		return Iterator::convert($entity, self::LOWER);
	}

	/**
	 * Covert array keys from lower to upper.
	 * @param  mixed
	 * @return array
	 */
	public static function upper($entity)
	{
		return Iterator::convert($entity, self::UPPER);
	}

	/**
	 * Convert all array keys from upper to lower.
	 * @param
	 */
	public static function records($records)
	{
		$items = [];
		foreach ($records as $record) {
			$items[] = ArrayHash::from(Iterator::lower($record));
		}
		return $items;
	}

}
