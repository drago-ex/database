<?php

declare(strict_types = 1);

/**
 * Drago Database
 * Package built on Nette Framework
 */

namespace Drago\Database;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;


/**
 * Entity base.
 */
class Entity implements ArrayAccess, IteratorAggregate, Countable
{
	/** @var mixed */
	protected $data;

	/** @var mixed */
	protected $modify;


	public function __construct(array $arr = [])
	{
		$this->data = $arr;
		foreach ($arr as $k => $v) {
			$this->$k = $v;
		}
	}


	/**
	 * Convert to array.
	 */
	public function toArray(): array
	{
		return (array) $this->data;
	}


	/**
	 * Modified data.
	 */
	public function getModify(): ?array
	{
		return $this->modify;
	}


	/**
	 * Retrieve an external iterator.
	 */
	public function getIterator()
	{
		return new ArrayIterator($this);
	}


	/**
	 * Whether a offset exists.
	 */
	public function offsetExists($offset)
	{
		return isset($this->$offset);
	}


	/**
	 * Offset to retrieve.
	 */
	public function offsetGet($offset)
	{
		return $this->$offset;
	}


	/**
	 * Offset to set.
	 */
	public function offsetSet($offset, $value)
	{
		$this->modify[$offset] = $value;
		$this->$offset = $value;
	}


	/**
	 * Offset to unset.
	 */
	public function offsetUnset($offset)
	{
		unset($this->$offset);
	}


	/**
	 * Count elements of an object.
	 */
	public function count()
	{
		return count((array) $this);
	}
}
