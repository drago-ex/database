<?php

declare(strict_types = 1);

/**
 * Drago Database
 * @copyright Zdeněk Papučík
 */
namespace Drago\Database;

/**
 * Repository base for iterate entity.
 * @package Drago\Database
 */
trait Repository
{
	/**
	 * Convert entity to array.
	 * @param  object $entity
	 * @return array
	 */
	public function toArray($entity)
	{
		$arr = [];
		foreach ($entity as $key => $value) {
			if (!is_null($value)) {
				$arr[$key] = $value;
			}
		}
		return $arr;
	}
}
