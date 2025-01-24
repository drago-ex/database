<?php

declare(strict_types=1);

/**
 * Drago Extension
 * Package built on Nette Framework
 */

namespace Drago\Attr;

use ReflectionClass;


/**
 * Trait for retrieving attributes from the repository.
 *
 * Provides methods to detect table information (name and primary key) using PHP attributes.
 */
trait AttributeDetection
{
	/**
	 * Detects and retrieves table information (name and primary key) from attributes.
	 *
	 * @throws AttributeDetectionException If the Table attribute is not present or does not contain the table name.
	 */
	private function getTableInfo(): Attributes
	{
		$ref = new ReflectionClass(static::class);
		$arr = [];

		// Retrieve all attributes of the current class
		foreach ($ref->getAttributes() as $attr) {
			$arr = $attr->getArguments();
		}

		// If no table name is found, throw an exception
		if (!isset($arr[0])) {
			throw new AttributeDetectionException(
				'In the model ' . static::class . ' you do not have a table name in the Table attribute.',
			);
		}

		// Return attributes (table name and primary key if available)
		return new Attributes(
			name: $arr[0],
			primaryKey: $arr[1] ?? null,
		);
	}


	/**
	 * Returns the name of the table.
	 *
	 * @throws AttributeDetectionException If the table name cannot be detected.
	 */
	public function getTableName(): string
	{
		return $this->getTableInfo()->name;
	}


	/**
	 * Returns the primary key of the table.
	 *
	 * @throws AttributeDetectionException If the primary key cannot be detected.
	 */
	public function getPrimaryKey(): ?string
	{
		return $this->getTableInfo()->primaryKey;
	}
}
