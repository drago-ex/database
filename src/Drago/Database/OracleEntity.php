<?php

declare(strict_types = 1);

/**
 * Drago Extension
 * Package built on Nette Framework
 */

namespace Drago\Database;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;
use Nette\Utils\Strings;


/**
 * Base for oracle entity.
 */
class OracleEntity implements ArrayAccess, IteratorAggregate, Countable
{
	public function __construct(array $arr = [])
	{
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


	final public function count()
	{
		return count((array) $this);
	}


	final public function getIterator()
	{
		return new ArrayIterator($this);
	}


	final public function offsetSet($nm, $val)
	{
		$this->$nm = $val;
	}


	final public function offsetGet($nm)
	{
		return $this->$nm;
	}


	final public function offsetExists($nm)
	{
		return isset($this->$nm);
	}


	final public function offsetUnset($nm)
	{
		unset($this->$nm);
	}
}
