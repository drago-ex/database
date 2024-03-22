<?php

declare(strict_types=1);

/**
 * Drago Extension
 * Package built on Nette Framework
 */

namespace Drago\Attr;

use ReflectionClass;


/**
 * Retrieving attributes from the repository.
 */
trait AttributeDetection
{
	/**
	 * Attribute detection.
	 * @throws AttributeDetectionException
	 */
	private function getTableInfo(): Attributes
	{
		$ref = new ReflectionClass(static::class);
		$arr = [];
		foreach ($ref->getAttributes() as $attr) {
			$arr = $attr->getArguments();
		}

		if (!isset($arr[0])) {
			throw new AttributeDetectionException(
				'In the model ' . static::class . ' you do not have a table name in the Table attribute.',
			);
		}

		return new Attributes(
			name: $arr[0],
			primaryKey: $arr[1] ?? null,
		);
	}


	/**
	 * The name of the table.
	 * @throws AttributeDetectionException
	 */
	public function getTableName(): string
	{
		return $this->getTableInfo()->name;
	}


	/**
	 * The primary key of the table.
	 * @throws AttributeDetectionException
	 */
	public function getPrimaryKey(): string|null
	{
		return $this->getTableInfo()->primaryKey;
	}
}
