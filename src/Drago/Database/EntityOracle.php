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
		parent::__construct(array_change_key_case($arr));
	}


	/**
	 * Returns items as array with converted keys to uppercase.
	 */
	public function toArrayUpper(): array
	{
		$data = (array) $this;
		return array_change_key_case($data, CASE_UPPER);
	}
}
