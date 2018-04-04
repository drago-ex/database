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
		$array = [];
		foreach ($entity as $key => $value) {
			switch ($convert) {
				case 'lower': $array[Strings::lower($key)] = $value; break;
				case 'upper': $array[Strings::upper($key)] = $value; break;
				default:
					$array[$key] = $value;
				break;
			}
		}
		return $array;
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
	public static function toLowerAll($rows)
	{
		$array = [];
		foreach ($rows as $row) {
			$array[] = ArrayHash::from(Iterator::lower($row));
		}
		return $array;
	}


	/**
	 * Convert one record (sql query) to lower case.
	 * @param array
	 * @return array
	 */
	public static function toLowerOne($row)
	{
		return ArrayHash::from(Iterator::lower($row));
	}

}
