<?php

/**
 * Drago Database
 * Copyright (c) 2015, Zdeněk Papučík
 */
namespace Drago\Database;

use Nette;
use Nette\Utils\Strings;
use stdClass;

/**
 * Iterator and convert keys in array to lowercase or uppercase.
 */
class Iterator
{
	use Nette\StaticClass;

	/**
	 * Convert keys in array.
	 */
	const
		/**
		 * Convert to lowercase.
		 */
		LOWER = 'lower',

		/**
		 * Convert to uppercase.
		 */
		UPPER = 'upper';

	/**
	 * Convert keys in array when the lower or upper parameter is added.
	 * @param string $convert
	 * @return array
	 */
	private static function convert(array $entity, $convert = null)
	{
		$arr = [];
		foreach ($entity as $key => $value) {
			switch ($convert) {
				case 'lower': $arr[Strings::lower($key)] = $value; break;
				case 'upper': $arr[Strings::upper($key)] = $value; break;
				default:
					$arr[$key] = $value;
				break;
			}
		}
		return $arr;
	}

	/**
	 * Convert entity to array.
	 * @return array
	 */
	public static function toArray(array $entity)
	{
		return Iterator::convert($entity);
	}

	/**
	 * Convert keys in array to lowercase.
	 * @return array
	 */
	public static function toLower(array $entity)
	{
		return Iterator::convert($entity, self::LOWER);
	}

	/**
	 * Convert keys in array to uppercase.
	 * @return array
	 */
	public static function toUpper(array $entity)
	{
		return Iterator::convert($entity, self::UPPER);
	}

	/**
	 * Convert keys in array to lowercase for all records.
	 * @return stdClass
	 */
	public static function toLowerAll(array $rows)
	{
		$arr = [];
		foreach ($rows as $row) {
			$arr[] = (object) Iterator::toLower($row);
		}
		return $arr;
	}

	/**
	 * Convert keys in array to lowercase for one record.
	 * @return stdClass
	 */
	public static function toLowerOne(array $row)
	{
		return (object) Iterator::toLower($row);
	}

}
