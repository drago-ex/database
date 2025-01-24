<?php

declare(strict_types=1);

/**
 * Drago Extension
 * Package built on Nette Framework
 */

namespace Drago\Database;

use Dibi\Row;


/**
 * Base for Oracle entity.
 *
 * This class extends the Dibi\Row class, specifically tailored for Oracle entities,
 * with functionality for handling keys in uppercase and converting data as needed.
 */
class EntityOracle extends Row
{
	/**
	 * Initializes the entity with the provided data and converts the keys to lowercase.
	 *
	 * @param array<string, mixed> $arr The data to initialize the entity with.
	 */
	public function __construct(array $arr = [])
	{
		parent::__construct($this->changeKey($arr));
	}


	/**
	 * Converts all keys in the entity to uppercase.
	 *
	 * @return array<string, mixed> The entity data with uppercase keys.
	 */
	public function toArrayUpper(): array
	{
		$data = (array) $this;
		return $this->changeKey($data, CASE_UPPER);
	}


	/**
	 * Changes the case of all keys in an array.
	 *
	 * @param array<string, mixed> $data The data array with keys to be changed.
	 * @param int $case The case to convert the keys to (either CASE_UPPER or CASE_LOWER).
	 * @return array<string, mixed> The array with keys changed to the specified case.
	 */
	private function changeKey(array $data, int $case = CASE_LOWER): array
	{
		return array_change_key_case($data, $case);
	}
}
