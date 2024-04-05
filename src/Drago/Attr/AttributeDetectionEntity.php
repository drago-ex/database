<?php

declare(strict_types=1);

/**
 * Drago Extension
 * Package built on Nette Framework
 */

namespace Drago\Attr;

use ReflectionClass;


trait AttributeDetectionEntity
{
	/**
	 * @throws AttributeDetectionException
	 */
	private function getEntityClass(): array
	{
		$ref = new ReflectionClass(static::class);
		$arr = [];
		foreach ($ref->getAttributes() as $attr) {
			$arr = $attr->getArguments();
		}

		if (!isset($arr[0])) {
			throw new AttributeDetectionException(
				'In the model ' . static::class . ' you do not have a class name in the Entity attribute.',
			);
		}

		if (!defined("$arr[0]::Table")) {
			throw new AttributeDetectionException(
				'The constant "Table" is not defined in the entity.',
			);

		} elseif (!defined("$arr[0]::ColumnId")) {
			throw new AttributeDetectionException(
				'The constant "ColumnId" is not defined in the entity.',
			);
		}

		return [
			$arr[0],
			$arr[0]::Table,
			$arr[0]::ColumnId,
		];
	}


	/**
	 * @throws AttributeDetectionException
	 */
	public function getClassName(): string
	{
		return $this->getEntityClass()[0];
	}


	/**
	 * @throws AttributeDetectionException
	 */
	public function getTableName(): string
	{
		return $this->getEntityClass()[1];
	}


	/**
	 * @throws AttributeDetectionException
	 */
	public function getPrimaryKey(): string
	{
		return $this->getEntityClass()[2];
	}
}
