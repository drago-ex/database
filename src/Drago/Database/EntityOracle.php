<?php

declare(strict_types=1);

/**
 * Drago Extension
 * Package built on Nette Framework
 */

namespace Drago\Database;

use Dibi\Row;


/**
 * Base class for Oracle entity.
 */
class EntityOracle extends Row
{
	/**
	 * Constructor for Oracle entity, changes array keys to uppercase.
	 */
	public function __construct(array $arr = [])
	{
		parent::__construct($this->changeKey($arr));
	}


	/**
	 * Returns items as an array with converted keys to uppercase.
	 */
	public function toArrayUpper(): array
	{
		$data = (array) $this;
		return $this->changeKey($data, CASE_UPPER);
	}


	/**
	 * Changes the case of all keys in an array.
	 */
	private function changeKey(array $data, int $case = CASE_LOWER): array
	{
		return array_change_key_case($data, $case);
	}
}
