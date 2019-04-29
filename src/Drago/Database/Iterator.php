<?php

declare(strict_types = 1);

/**
 * Drago Database
 * @copyright Zdeněk Papučík
 */
namespace Drago\Database;

/**
 * Entity Iteration.
 * @package Drago\Database
 */
trait Iterator
{
	/**
	 * Convert entity to array.
	 * @return array
	 */
	public function toArray($entity): array
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
