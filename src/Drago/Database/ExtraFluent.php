<?php

declare(strict_types=1);

/**
 * Drago Extension
 * Package built on Nette Framework
 */

namespace Drago\Database;

use Dibi\Exception;
use Dibi\Fluent;


/**
 * @template T
 */
class ExtraFluent extends Fluent
{
	/** @var string|null */
	public ?string $className = null;


	/**
	 * Set the table for the query.
	 *
	 * @param string $table Table name.
	 * @param mixed ...$args Additional arguments for the query.
	 * @return $this Fluent instance.
	 */
	public function from($table, ...$args): self
	{
		parent::from($table, ...$args);
		return $this;
	}


	/**
	 * Select fields for the query.
	 *
	 * @param mixed ...$field Fields to be selected.
	 * @return $this Fluent instance.
	 */
	public function select(...$field): self
	{
		parent::select(...$field);
		return $this;
	}


	/**
	 * Add where condition to the query.
	 *
	 * @param mixed ...$cond Conditions for the WHERE clause.
	 * @return $this Fluent instance.
	 */
	public function where(...$cond): self
	{
		parent::where(...$cond);
		return $this;
	}


	/**
	 * Add AND condition to the query.
	 *
	 * @param mixed ...$cond Conditions for the AND clause.
	 * @return $this Fluent instance.
	 */
	public function and(...$cond): self
	{
		parent::and(...$cond);
		return $this;
	}


	/**
	 * Add OR condition to the query.
	 *
	 * @param mixed ...$cond Conditions for the OR clause.
	 * @return $this Fluent instance.
	 */
	public function or(...$cond): self
	{
		parent::or(...$cond);
		return $this;
	}


	/**
	 * Add order by condition to the query.
	 *
	 * @param mixed ...$field Fields for the ORDER BY clause.
	 * @return $this Fluent instance.
	 */
	public function orderBy(...$field): self
	{
		parent::orderBy(...$field);
		return $this;
	}


	/**
	 * Delete records based on conditions.
	 *
	 * @param mixed ...$cond Conditions for the DELETE query.
	 * @return $this Fluent instance.
	 */
	public function delete(...$cond): self
	{
		parent::delete(...$cond);
		return $this;
	}


	/**
	 * Insert values into the table.
	 *
	 * @param mixed ...$cond Values or SQL fragments to insert.
	 * @return $this Fluent instance.
	 */
	public function insert(...$cond): self
	{
		parent::insert(...$cond);
		return $this;
	}


	/**
	 * Update records in the table.
	 *
	 * @param mixed ...$cond Values or SQL fragments to update.
	 * @return $this Fluent instance.
	 */
	public function update(...$cond): self
	{
		parent::update(...$cond);
		return $this;
	}


	/**
	 * Set values for UPDATE queries.
	 *
	 * @param mixed ...$args Column-value pairs.
	 * @return $this
	 */
	public function set(...$args): self
	{
		parent::set(...$args);
		return $this;
	}


	/**
	 * Set values for INSERT queries.
	 *
	 * @param mixed ...$args Column-value pairs.
	 * @return $this
	 */
	public function values(...$args): self
	{
		parent::values(...$args);
		return $this;
	}


	/**
	 * Set the table for the INSERT query.
	 *
	 * @param mixed ...$cond Table name or SQL fragments.
	 * @return $this Fluent instance.
	 */
	public function into(...$cond): self
	{
		parent::into(...$cond);
		return $this;
	}


	/**
	 * Execute the query and return a single record.
	 *
	 * @return T|null The record or null if not found.
	 * @throws Exception
	 */
	public function record(): mixed
	{
		return $this->execute()
			->setRowClass($this->className)
			->fetch();
	}


	/**
	 * Execute the query and return multiple records.
	 *
	 * @return T[] List of records.
	 * @throws Exception
	 */
	public function recordAll(?int $offset = null, ?int $limit = null): array
	{
		return $this->execute()
			->setRowClass($this->className)
			->fetchAll($offset, $limit);
	}
}
