<?php declare(strict_types=1);

namespace Drago\Database;


/**
 * An entity of Oracle, whose columns are uppercase: column USED_COUNT fills $usedCount.
 */
class EntityOracle extends Entity
{
	/** @param array<string, mixed> $arr column => value */
	public function __construct(array $arr = [])
	{
		parent::__construct(array_change_key_case($arr));
	}


	/**
	 * Returns the values keyed by the uppercase column names.
	 * @return array<string, mixed>
	 */
	public function toColumns(): array
	{
		return array_change_key_case(parent::toColumns(), CASE_UPPER);
	}
}
