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
	 * Convert entity to array.
	 * @param  mixed
	 * @return array
	 */
	public static function toArray($entity)
	{
		return Iterator::convert($entity);
	}

	/**
	 * Convert to array and lower case.
	 * @param  mixed
	 * @return array
	 */
	public static function toLower($entity)
	{
		return Iterator::convert($entity, self::LOWER);
	}

	/**
	 * Convert to array and upper case.
	 * @param  mixed
	 * @return array
	 */
	public static function toUpper($entity)
	{
		return Iterator::convert($entity, self::UPPER);
	}

	/**
	 * Convert all records (sql query) to lower case.
	 * @param array
	 * @return array
	 */
	public static function toLowerAll($records)
	{
		$items = [];
		foreach ($records as $record) {
			$items[] = ArrayHash::from(Iterator::lower($record));
		}
		return $items;
	}


	/**
	 * Convert one record (sql query) to lower case.
	 * @param array
	 * @return array
	 */
	public static function toLowerOne($records)
	{
		return ArrayHash::from(Iterator::lower($records));
	}

}
