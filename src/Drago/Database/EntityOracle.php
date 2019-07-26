<?php

declare(strict_types = 1);

/**
 * Drago Database
 * Package built on Nette Framework
 */
namespace Drago\Database;


/**
 * Entity base.
 * @package Drago\Database
 */
class EntityOracle extends Entity
{
	public function __construct(array $arr = [])
	{
		$this->data = $arr;
		foreach ($arr as $k => $v) {
      $k = Strings::lower($k);
			$this->$k = $v;
		}
	}


	/**
	 * Offset to set.
	 */
	public function offsetSet($offset, $value)
	{
		$this->modify[Strings::upper($offset)] = $value;
		$this->$offset = $value;
	}
}
