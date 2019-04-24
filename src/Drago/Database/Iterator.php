<?php

declare(strict_types = 1);

/**
 * Drago Database
 * @copyright Zdeněk Papučík
 */
namespace Drago\Database;

use Dibi;
use Nette;
use Nette\Utils\Strings;
use Tracy\Debugger;

/**
 * Iterator and convert keys in array to lowercase or uppercase.
 * @package Drago\Database
 */
class Iterator
{
	use Nette\StaticClass;

	const
		LOWER = 'lower',
		UPPER = 'upper';


	/**
	 * Convert keys in array when the lower or upper parameter is added.
	 */
	private static function convert($entity, string $convert = null): array
	{
		$arr = [];
		foreach ($entity as $key => $value) {
			switch ($convert) {
				case 'lower': $arr[Strings::lower($key)] = $value; break;
				case 'upper': $arr[Strings::upper($key)] = $value; break;
				default: $arr[$key] = $value; break;
			}
		}
		return $arr;
	}


	/**
	 * Convert entity to array.
	 */
	public static function toArray($entity): array
	{
		return Iterator::convert($entity);
	}


	/**
	 * Convert keys in array to uppercase.
	 */
	public static function toUpper($entity): array
	{
		return Iterator::convert($entity, self::UPPER);
	}


	/**
	 * Convert keys in array to lowercase.
	 */
	public static function toLower($entity): array
	{
		return Iterator::convert($entity, self::LOWER);
	}


	/**
	 * Convert keys in array to lowercase for one record.
	 */
	public static function toLowerOne(?Dibi\Row $row): ?Dibi\Row
	{
		$result = $row ? new Dibi\Row(Iterator::toLower($row)) : null;
		return $result;
	}


	/**
	 * Convert keys in array to lowercase for all records.
	 */
	public static function toLowerAll(iterable $rows): iterable
	{
		$arr = [];
		foreach ($rows as $row) {
			$arr[] = new Dibi\Row(Iterator::toLower($row));
		}
		return $arr;
	}
}
