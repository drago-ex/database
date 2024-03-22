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
	 */
	private function getTableInfo(): Attributes
	{
		$ref = new ReflectionClass(static::class);
		$arr = [];
		foreach ($ref->getAttributes() as $attr) {
			$arr = $attr->getArguments();
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
		if (!isset($this->getTableInfo()->name)) {
			throw new AttributeDetectionException(
				'In the model ' . static::class . ' you do not have a table name in the Table attribute.',
			);
		}
		return $this->getTableInfo()->name;
	}


	/**
	 * The primary key of the table.
	 */
	public function getPrimaryKey(): string|null
	{
		return $this->getTableInfo()->primaryKey;
	}
}
