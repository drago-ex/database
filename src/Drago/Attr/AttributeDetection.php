<?php

declare(strict_types=1);

namespace Drago\Attr;

use Dibi\Row;
use ReflectionClass;


/** Retrieving attributes from the repository. */
trait AttributeDetection
{
	/** @throws AttributeDetectionException */
	private function getAttributes(): Attributes
	{
		$reflectionClass = new ReflectionClass(static::class);
		$attributes = [];

		foreach ($reflectionClass->getAttributes() as $attribute) {
			/** @var array<string|int, mixed> $attributes */
			$attributes = $attribute->getArguments();
		}

		if (!isset($attributes[0]) || !is_string($attributes[0])) {
			throw new AttributeDetectionException(
				sprintf(
					'In the model %s you do not have a table name in the From attribute.',
					static::class,
				),
			);
		}

		$class = isset($attributes['class']) && is_string($attributes['class']) ? $attributes['class'] : null;

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
			primaryKey: isset($attributes[1]) && is_string($attributes[1]) ? $attributes[1] : null,
			class: $class,
		);
	}


	/** @throws AttributeDetectionException */
	public function getTableName(): string
	{
		return $this->getAttributes()->name;
	}


	/** @throws AttributeDetectionException */
	public function getPrimaryKey(): string
	{
		$primaryKey = $this->getAttributes()->primaryKey;

		if ($primaryKey === null) {
			throw new AttributeDetectionException(
				sprintf('In the model %s you do not have a primary key in the Table attribute.', static::class),
			);
		}

		return $primaryKey;
	}


	/** @throws AttributeDetectionException */
	public function getClassName(): ?string
	{
		return $this->getAttributes()->class;
	}
}
