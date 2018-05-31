<?php

/**
 * Drago Database
 * Copyright (c) 2015, Zdeněk Papučík
 */
namespace Drago\Database;

use Nette;
use Nette\Utils\Strings;

use Dibi;

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
		// Convert to lowercase.
		LOWER = 'lower',

		// Convert to uppercase.
		UPPER = 'upper';

	/**
	 * Convert keys in array when the lower or upper parameter is added.
	 * @param mixed $entity
	 * @param string $convert
	 * @return array
	 */
	private static function convert($entity, $convert = null)
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
	 * @param mixed $entity
	 * @return array
	 */
	public static function toArray($entity)
	{
		return Iterator::convert($entity);
	}

	/**
	 * Convert keys in array to lowercase.
	 * @param mixed $entity
	 * @return array
	 */
	public static function toLower($entity)
	{
		return Iterator::convert($entity, self::LOWER);
	}

	/**
	 * Convert keys in array to uppercase.
	 * @param mixed $entity
	 * @return array
	 */
	public static function toUpper($entity)
	{
		return Iterator::convert($entity, self::UPPER);
	}

	/**
	 * Convert keys in array to lowercase for all records.
	 * @param mixed $rows
	 * @return Dibi\Row
	 */
	public static function toLowerAll($rows)
	{
		$arr = [];
		foreach ($rows as $row) {
			$arr[] = new Dibi\Row(Iterator::toLower($row));
		}
		return $arr;
	}

	/**
	 * Convert keys in array to lowercase for one record.
	 * @param mixed $row
	 * @return Dibi\Row
	 */
	public static function toLowerOne($row)
	{
		return new Dibi\Row(Iterator::toLower($row));
	}

}
