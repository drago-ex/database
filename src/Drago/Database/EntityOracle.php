<?php

/**
 * Drago Extension
 * Package built on Nette Framework
 */

declare(strict_types=1);

namespace Drago\Database;

use Nette\Utils\Strings;


/**
 * Base for oracle entity.
 */
class EntityOracle implements \ArrayAccess, \IteratorAggregate, \Countable
{
	public function __construct(array $arr = [])
	{
		foreach ($arr as $k => $v) {
			$this->{Strings::lower($k)} = $v;
		}
	}


	public function toArray(): array
	{
		return (array) $this;
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
		return new \ArrayIterator($this);
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
