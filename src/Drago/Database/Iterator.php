<?php

// Enable strict mode.
declare(strict_types = 1);

/**
 * Drago Database
 * Copyright (c) 2015, Zdeněk Papučík
 */
namespace Drago\Database;

use Nette;
use Nette\Utils\Strings;
use Nette\Utils\ArrayHash;

/**
 * Iterator and convert keys in array to lowercase or uppercase.
 */
class Iterator
{
	use Nette\StaticClass;

	/**
	 * Convert keys in array.
	 */
	public const

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
	 */
	private static function convert(array $entity, string $convert = null): array
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
	 */
	public static function toArray(array $entity): array
	{
		return Iterator::convert($entity);
	}

	/**
	 * Convert keys in array to lowercase.
	 */
	public static function toLower(array $entity): array
	{
		return Iterator::convert($entity, self::LOWER);
	}

	/**
	 * Convert keys in array to uppercase.
	 */
	public static function toUpper(array $entity): array
	{
		return Iterator::convert($entity, self::UPPER);
	}

	/**
	 * Convert keys in array to lowercase for all records.
	 */
	public static function toLowerAll(array $rows): ArrayHash
	{
		$arr = [];
		foreach ($rows as $row) {
			$arr[] = ArrayHash::from(Iterator::toLower($row));
		}
		return $arr;
	}

	/**
	 * Convert keys in array to lowercase for one record.
	 */
	public static function toLowerOne(array $row): ArrayHash
	{
		return ArrayHash::from(Iterator::toLower($row));
	}

}
