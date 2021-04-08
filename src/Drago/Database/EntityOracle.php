<?php

/**
 * Drago Extension
 * Package built on Nette Framework
 */

declare(strict_types=1);

namespace Drago\Database;

use Dibi\Row;
use Nette\Utils\Strings;


/**
 * Base for oracle entity.
 */
class EntityOracle extends Row
{
	public function __construct(array $arr = [])
	{
		parent::__construct($arr);
		foreach ($arr as $k => $v) {
			$k = Strings::lower($k);
			$this->$k = $v;
		}
	}


	/**
	 * Returns items as array with converted keys to uppercase.
	 */
	public function toArrayUpper(): array
	{
		$data = [];
		foreach ($this as $k => $v) {
			$data[Strings::upper($k)] = $v;
		}
		return $data;
	}
}
