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
	private function attributes(): Attributes
	{
		$ref = new ReflectionClass(static::class);
		$arr = [];
		foreach ($ref->getAttributes() as $attr) {
			$arr = $attr->getArguments();
		}
		return new Attributes(
			table: $arr[0],
			id: $arr[1] ?? null,
		);
	}


	/**
	 * Table name.
	 * @throws AttributeDetectionException
	 */
	public function getTable(): string
	{
		if (!isset($this->attributes()->table)) {
			throw new AttributeDetectionException(
				'In the repository ' . static::class . ' you do not have a table name in the Table attribute.',
			);
		}
		return $this->attributes()->table;
	}


	/**
	 * Table primary key.
	 */
	public function getId(): string|null
	{
		return $this->attributes()->id;
	}
}
