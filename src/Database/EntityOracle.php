<?php

declare(strict_types=1);

namespace Drago\Database;

use Dibi\Row;


class EntityOracle extends Row
{
	public function __construct(array $arr = [])
	{
		parent::__construct(array_change_key_case($arr));
	}


	/**
	 * Returns items as an array with converted keys to uppercase.
	 * @return array<string, mixed>
	 */
	public function toArrayUpper(): array
	{
		$data = (array) $this;
		return array_change_key_case($data, CASE_UPPER);
	}
}
