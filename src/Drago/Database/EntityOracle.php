<?php

declare(strict_types=1);

/**
 * Drago Extension
 * Package built on Nette Framework
 */

namespace Drago\Database;

use Dibi\Row;


/**
 * Base for oracle entity.
 */
class EntityOracle extends Row
{
	public function __construct(array $arr = [])
	{
		parent::__construct($this->changeKey($arr));
	}


	/**
	 * Returns items as array with converted keys to uppercase.
	 */
	public function toArrayUpper(): array
	{
		$data = (array) $this;
		return $this->changeKey($data, CASE_UPPER);
	}


	/**
	 * Changes the case of all keys in an array.
	 */
	public function changeKey(array $data, int $case = CASE_LOWER): array
	{
		return array_change_key_case($data, $case);
	}
}
