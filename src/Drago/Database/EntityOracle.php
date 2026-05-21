<?php

declare(strict_types=1);

namespace Drago\Database;

use Dibi\Row;


/** Base class for Oracle entity. */
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
		/** @var array<string, mixed> $data */
		$data = (array) $this;
		return array_change_key_case($data, CASE_UPPER);
	}
}
