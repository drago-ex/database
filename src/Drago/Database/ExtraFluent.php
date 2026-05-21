<?php

declare(strict_types=1);

namespace Drago\Database;

use Dibi\Exception;
use Dibi\Fluent;
use Dibi\Result;
use Dibi\Row;


/** @template-covariant T of object */
class ExtraFluent extends Fluent
{
	/** @var class-string<Row>|null */
	public ?string $className = null;


	/** @return $this */
	public function from(mixed $table, mixed ...$args): self
	{
		parent::from($table, ...$args);
		return $this;
	}


	/** @return $this */
	public function select(mixed ...$field): self
	{
		parent::select(...$field);
		return $this;
	}


	/** @return $this */
	public function where(mixed ...$cond): self
	{
		parent::where(...$cond);
		return $this;
	}


	/** @return $this */
	public function and(mixed ...$cond): self
	{
		parent::and(...$cond);
		return $this;
	}


	/** @return $this */
	public function or(mixed ...$cond): self
	{
		parent::or(...$cond);
		return $this;
	}


	/** @return $this */
	public function orderBy(mixed ...$field): self
	{
		parent::orderBy(...$field);
		return $this;
	}


	/** @return $this */
	public function delete(mixed ...$cond): self
	{
		parent::delete(...$cond);
		return $this;
	}


	/** @return $this */
	public function insert(mixed ...$cond): self
	{
		parent::insert(...$cond);
		return $this;
	}


	/** @return $this */
	public function update(mixed ...$cond): self
	{
		parent::update(...$cond);
		return $this;
	}


	/** @return $this */
	public function set(mixed ...$args): self
	{
		parent::set(...$args);
		return $this;
	}


	/** @return $this */
	public function values(mixed ...$args): self
	{
		parent::values(...$args);
		return $this;
	}


	/** @return $this */
	public function into(mixed ...$cond): self
	{
		parent::into(...$cond);
		return $this;
	}


	/**
	 * @return T|null
	 * @throws Exception
	 */
	public function record(): ?object
	{
		$result = $this->execute();
		if ($result instanceof Result) {

			/** @var T|null */
			return $result->setRowClass($this->className)->fetch();
		}

		return null;
	}


	/**
	 * @return T[]
	 * @throws Exception
	 */
	public function recordAll(?int $offset = null, ?int $limit = null): array
	{
		$result = $this->execute();
		if ($result instanceof Result) {

			/** @var T[] $data */
			$data = $result->setRowClass($this->className)->fetchAll($offset, $limit);
			return $data;
		}

		return [];
	}
}
