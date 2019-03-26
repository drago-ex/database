<?php declare(strict_types = 1);

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
	 */
	private static function convert($entity, string $convert = null): array
	{
		$arr = [];
		if ($entity) {
			foreach ($entity as $key => $value) {
				switch ($convert) {
					case 'lower': $arr[Strings::lower($key)] = $value; break;
					case 'upper': $arr[Strings::upper($key)] = $value; break;
					default:
						$arr[$key] = $value;
					break;
				}
			}
		}
		return $arr ? $arr : $entity;
	}


	/**
	 * Convert entity to array.
	 */
	public static function toArray($entity): array
	{
		return Iterator::convert($entity);
	}


	/**
	 * Convert keys in array to lowercase.
	 */
	public static function toLower($entity): array
	{
		return Iterator::convert($entity, self::LOWER);
	}


	/**
	 * Convert keys in array to uppercase.
	 */
	public static function toUpper($entity): array
	{
		return Iterator::convert($entity, self::UPPER);
	}


	/**
	 * Convert keys in array to lowercase for all records.
	 */
	public static function toLowerAll($rows): Dibi\Row
	{
		$arr = [];
		if ($rows) {
			foreach ($rows as $row) {
				$arr[] = new Dibi\Row(Iterator::toLower($row));
			}
		}
		return $arr ? $arr : $rows;
	}


	/**
	 * Convert keys in array to lowercase for one record.
	 */
	public static function toLowerOne($row): Dibi\Row
	{
		return new Dibi\Row(Iterator::toLower($row));
	}
}
