<?php

declare(strict_types = 1);

/**
 * Drago Extension
 * Package built on Nette Framework
 */

namespace Drago\Database;

use Nette\Utils\Strings;


/**
 * Entity base, converts the index from upper to lower.
 */
class EntityConvertor extends Entity
{
	public function __construct(array $arr = [])
	{
		parent::__construct($arr);
		$this->data = $arr;
		foreach ($arr as $k => $v) {
			$k = Strings::lower($k);
			$this->$k = $v;
		}
	}


	/**
	 * Convert to array.
	 */
	public function toArray(): array
	{
		$data = [];
		foreach ($this->data as $k => $v) {
			$data[Strings::lower($k)] = $v;
		}
		return $data;
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
