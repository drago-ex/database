<?php

declare(strict_types=1);

namespace Drago\Attr;

use Dibi\Row;
use ReflectionClass;


trait AttributeDetection
{
	/** @var array<class-string, Attributes> */
	private static array $attributesCache = [];


	/** @throws AttributeDetectionException */
	private function getAttributes(): Attributes
	{
		return self::$attributesCache[static::class] ??= $this->detectAttributes();
	}


	/** @throws AttributeDetectionException */
	private function detectAttributes(): Attributes
	{
		$reflectionClass = new ReflectionClass(static::class);
		$attributes = $reflectionClass->getAttributes(Table::class);

		if ($attributes === []) {
			throw new AttributeDetectionException(
				sprintf(
					'In the model %s you do not have a table name in the Table attribute.',
					static::class,
				),
			);
		}

		$table = $attributes[0]->newInstance();

		/** @var class-string<Row>|null $entityClass */
		$entityClass = $table->entity ?? $table->class;

		if ($entityClass !== null && !is_subclass_of($entityClass, Row::class)) {
			throw new AttributeDetectionException(
				sprintf(
					'Class "%s" in the Table attribute of %s is not an instance of Dibi\Row.',
					$entityClass,
					static::class,
				),
			);
		}

		return new Attributes(
			name: $table->name,
			primaryKey: $table->primaryKey,
			entity: $entityClass,
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
		return $this->getEntityClassName();
	}


	/** @throws AttributeDetectionException */
	public function getEntityClassName(): ?string
	{
		return $this->getAttributes()->entity;
	}
}
