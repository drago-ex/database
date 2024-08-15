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
	private function getAttributes(): Attributes
	{
		$ref = new ReflectionClass(static::class);
		$arr = [];
		foreach ($ref->getAttributes() as $attr) {
			$arr = $attr->getArguments();
		}

		if (!isset($arr[0])) {
			throw new AttributeDetectionException(
				'In the model ' . static::class . ' you do not have a table name in the From attribute.',
			);
		}

		return new Attributes(
			name: $arr[0],
			primaryKey: $arr[1] ?? null,
			class: $arr['class'] ?? null,
		);
	}


	/**
	 * The name of the table.
	 * @throws AttributeDetectionException
	 */
	public function getTableName(): string
	{
		return $this->getAttributes()
            ->name;
	}


	/**
	 * The primary key of the table.
	 * @throws AttributeDetectionException
	 */
	public function getPrimaryKey(): string
	{
		if ($this->getAttributes()->primaryKey === null) {
			throw new AttributeDetectionException(
				'In the model ' . static::class . ' you do not have a primary key name in the From attribute.',
			);
		}
		return $this->getAttributes()
            ->primaryKey;
	}


	/**
	 * @throws AttributeDetectionException
	 */
	public function getClassName(): string
	{
		if ($this->getAttributes()->class === null) {
			throw new AttributeDetectionException(
				'In the model ' . static::class . ' you do not have a class name in the From attribute.',
			);
		}
		return $this->getAttributes()
            ->class;
	}
}
