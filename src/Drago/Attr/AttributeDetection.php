<?php

declare(strict_types=1);

/**
 * Drago Extension
 * Package built on Nette Framework
 */

namespace Drago\Attr;

use Dibi\Row;
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
		$reflectionClass = new ReflectionClass(static::class);
		$attributes = [];

		foreach ($reflectionClass->getAttributes() as $attribute) {
			$attributes = $attribute->getArguments();
		}

		if (!isset($attributes[0])) {
			throw new AttributeDetectionException(
				sprintf(
					'In the model %s you do not have a table name in the From attribute.',
					static::class,
				),
			);
		}

		$class = $attributes['class'] ?? null;

		if ($class !== null && !is_subclass_of($class, Row::class)) {
			throw new AttributeDetectionException(
				sprintf(
					'Class "%s" in the From attribute of %s is not an instance of Dibi\Row.',
					$class,
					static::class,
				),
			);
		}

		return new Attributes(
			name: $attributes[0],
			primaryKey: $attributes[1] ?? null,
			class: $class,
		);
	}


	/**
	 * The name of the table.
	 * @throws AttributeDetectionException
	 */
	public function getTableName(): string
	{
		return $this->getAttributes()->name;
	}


	/**
	 * The primary key of the table.
	 * @throws AttributeDetectionException
	 */
	public function getPrimaryKey(): string
	{
		$primaryKey = $this->getAttributes()->primaryKey;

		// Ensure primary key is present
		if ($primaryKey === null) {
			throw new AttributeDetectionException(
				sprintf('In the model %s you do not have a primary key in the Table attribute.', static::class),
			);
		}

		return $primaryKey;
	}


	/**
	 * Row class for fetching object.
	 * @throws AttributeDetectionException
	 */
	public function getClassName(): ?string
	{
		return $this->getAttributes()->class;
	}
}
