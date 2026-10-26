<?php

declare(strict_types=1);

namespace Drago\Database;

use Dibi\Exception;
use Dibi\Fluent;
use Dibi\Result;
use Dibi\Row;


/**
 * Dibi's Fluent that fetches records as the entity class. The clauses run through Fluent::__call() (which
 * returns static); the tags below only give them the type of this class, so chains keep record()/recordAll().
 *
 * @template T of Row
 * @method $this select(mixed ...$field)
 * @method $this distinct()
 * @method $this from(string $table, mixed ...$args)
 * @method $this where(mixed ...$cond)
 * @method $this groupBy(mixed ...$field)
 * @method $this having(mixed ...$cond)
 * @method $this orderBy(mixed ...$field)
 * @method $this limit(int|false $limit)
 * @method $this offset(int|false $offset)
 * @method $this join(mixed ...$table)
 * @method $this leftJoin(mixed ...$table)
 * @method $this innerJoin(mixed ...$table)
 * @method $this rightJoin(mixed ...$table)
 * @method $this outerJoin(mixed ...$table)
 * @method $this union(Fluent $fluent)
 * @method $this unionAll(Fluent $fluent)
 * @method $this as(mixed ...$field)
 * @method $this on(mixed ...$cond)
 * @method $this and(mixed ...$cond)
 * @method $this or(mixed ...$cond)
 * @method $this using(mixed ...$cond)
 * @method $this update(mixed ...$cond)
 * @method $this insert(mixed ...$cond)
 * @method $this delete(mixed ...$cond)
 * @method $this into(mixed ...$cond)
 * @method $this values(mixed ...$cond)
 * @method $this set(mixed ...$args)
 * @method $this asc()
 * @method $this desc()
 */
class ExtraFluent extends Fluent
{
	/** @var class-string<T>|null */
	public ?string $className = null;


	/**
	 * @return T|null The record or null if not found.
	 * @throws Exception
	 */
	public function record(): ?Row
	{
		$result = $this->execute();
		if ($result === null) {
			return null;
		}

		/** @var T|null $row */
		$row = $this->applyRowClass($result)->fetch();
		return $row;
	}


	/**
	 * @return list<T> List of records.
	 * @throws Exception
	 */
	public function recordAll(): array
	{
		$result = $this->execute();
		if ($result === null) {
			return [];
		}

		/** @var list<T> $rows */
		$rows = $this->applyRowClass($result)->fetchAll();
		return $rows;
	}


	/**
	 * Without an entity class, Dibi's default Row class is kept;
	 * setting null would make Dibi return plain arrays.
	 */
	private function applyRowClass(Result $result): Result
	{
		return $this->className === null
			? $result
			: $result->setRowClass($this->className);
	}
}
