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
	public function getIterator(): ArrayIterator
	{
		$arr = (array) $this;
		return new ArrayIterator($arr);
	}


	/**
	 * Whether a offset exists.
	 * @param mixed $offset
	 */
	public function offsetExists($offset): bool
	{
		return isset($this->$offset);
	}


	/**
	 * Offset to retrieve.
	 * @param mixed $offset
	 */
	public function offsetGet($offset)
	{
		return $this->$offset;
	}


	/**
	 * Offset to set.
	 * @param mixed $offset
	 * @param mixed $value
	 */
	public function offsetSet($offset, $value): void
	{
		$this->modify[$offset] = $value;
		$this->$offset = $value;
	}


	/**
	 * Offset to unset.
	 * @param mixed $offset
	 */
	public function offsetUnset($offset): void
	{
		unset($this->$offset);
	}


	/**
	 * Count elements of an object.
	 */
	public function count(): int
	{
		return count((array) $this);
	}
}
