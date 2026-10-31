<?php declare(strict_types=1);

namespace Drago\Database;

use Closure;
use Dibi\Row;
use Drago\Utils\CaseConverter;
use ReflectionClass;
use ReflectionEnum;
use ReflectionNamedType;
use ReflectionProperty;


/**
 * A record whose properties are the camelCase names of its snake_case columns: column used_count fills $usedCount.
 * An entity that declares a property named exactly as the column ($used_count) keeps that name.
 * A property declared as bool or as a backed enum gets its column value converted; Dibi writes both back as scalars.
 */
class Entity extends Row
{
	/** @var array<class-string, array<string, Closure(mixed): mixed>> property => conversion of its column value */
	private static array $conversions = [];

	/** @var array<class-string, array<string, string>> column => property, filled as the columns come */
	private static array $propertyNames = [];


	/** @param array<string, mixed> $arr column => value */
	public function __construct(array $arr = [])
	{
		$conversions = self::$conversions[static::class] ??= self::detectConversions(static::class);
		$properties = [];
		foreach ($arr as $column => $value) {
			$property = self::$propertyNames[static::class][$column] ??= property_exists($this, $column)
				? $column
				: CaseConverter::camelCase($column);
			$properties[$property] = isset($conversions[$property]) && $value !== null
				? $conversions[$property]($value)
				: $value;
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


	/**
	 * Returns the conversions of the properties declared as bool or as a backed enum.
	 * @param class-string $class
	 * @return array<string, Closure(mixed): mixed>
	 */
	private static function detectConversions(string $class): array
	{
		$conversions = [];
		foreach ((new ReflectionClass($class))->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
			$type = $property->getType();
			if ($property->isStatic() || !$type instanceof ReflectionNamedType) {
				continue;
			}

			$name = $type->getName();
			if ($name === 'bool') {
				$conversions[$property->getName()] = fn(mixed $value): bool => (bool) $value;

			} elseif (is_subclass_of($name, \BackedEnum::class)) {
				$intBacked = (string) (new ReflectionEnum($name))->getBackingType() === 'int';
				$conversions[$property->getName()] = fn(mixed $value): \BackedEnum => $value instanceof $name
					? $value
					: $name::from($intBacked ? (int) $value : (string) $value);
			}
		}

		return $conversions;
	}
}
