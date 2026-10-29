<?php declare(strict_types=1);

namespace Drago\Database;

use Dibi\Row;
use Drago\Utils\CaseConverter;


/**
 * A record whose properties are the camelCase names of its snake_case columns: column used_count fills $usedCount.
 * An entity that declares a property named exactly as the column ($used_count) keeps that name.
 */
class Entity extends Row
{
	/** @param array<string, mixed> $arr column => value */
	public function __construct(array $arr = [])
	{
		$properties = [];
		foreach ($arr as $column => $value) {
			$properties[property_exists($this, $column) ? $column : CaseConverter::camelCase($column)] = $value;
		}

		parent::__construct($properties);
	}


	/**
	 * Converts camelCase keys to the snake_case column names; keys without a lowercase letter
	 * (ID, SAMPLE) are already column names and stay as they are.
	 * @param array<string, mixed> $values
	 * @return array<string, mixed>
	 */
	public static function toColumnNames(array $values): array
	{
		$columns = [];
		foreach ($values as $key => $value) {
			$columns[preg_match('/[a-z]/', $key) ? CaseConverter::snakeCase($key) : $key] = $value;
		}

		return $columns;
	}


	/**
	 * Returns the values keyed by the column names.
	 * @return array<string, mixed>
	 */
	public function toColumns(): array
	{
		/** @var array<string, mixed> $values */
		$values = (array) $this;
		return self::toColumnNames($values);
	}
}
