<?php

/**
 * Test: Drago\Database\ExtraFluent
 */

declare(strict_types=1);

use Dibi\Connection;
use Dibi\Result;
use Dibi\ResultDriver;
use Dibi\Row;
use Drago\Database\ExtraFluent;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


/** Returns fixed rows, so fetching can be tested without a database server. */
class ArrayResultDriver implements ResultDriver
{
	private int $position = 0;


	/** @param list<array<string, mixed>> $rows */
	public function __construct(
		private array $rows,
	) {
	}


	public function getRowCount(): int
	{
		return count($this->rows);
	}


	public function seek(int $row): bool
	{
		$this->position = $row;
		return isset($this->rows[$row]);
	}


	public function fetch(bool $type): ?array
	{
		return $this->rows[$this->position++] ?? null;
	}


	public function free(): void
	{
	}


	public function getResultColumns(): array
	{
		return [];
	}


	public function getResultResource(): mixed
	{
		return null;
	}


	public function unescapeBinary(string $value): string
	{
		return $value;
	}
}


/** @extends ExtraFluent<Row> */
class ArrayFluent extends ExtraFluent
{
	/** @param list<array<string, mixed>> $rows */
	public function __construct(
		private array $rows,
	) {
		parent::__construct(new Connection(['driver' => 'mysqli', 'lazy' => true]));
	}


	public function execute(?string $return = null): Result
	{
		return new Result(new ArrayResultDriver($this->rows), normalize: false);
	}
}


test('Without an entity class, records are fetched as Dibi rows', function () {
	$fluent = new ArrayFluent([['id' => 1, 'sample' => 'Hello'], ['id' => 2, 'sample' => 'World']]);

	$row = $fluent->record();
	Assert::type(Row::class, $row);
	Assert::same('Hello', $row->sample);

	$rows = (new ArrayFluent([['id' => 1], ['id' => 2]]))->recordAll();
	Assert::count(2, $rows);
	Assert::type(Row::class, $rows[0]);
});


test('With an entity class, records are fetched as entities', function () {
	$fluent = new ArrayFluent([['id' => 1, 'sample' => 'Hello']]);
	$fluent->className = TestEntity::class;

	$row = $fluent->record();
	Assert::type(TestEntity::class, $row);
	Assert::same(1, $row->id);

	$fluent = new ArrayFluent([['id' => 1, 'sample' => 'Hello']]);
	$fluent->className = TestEntity::class;
	Assert::type(TestEntity::class, $fluent->recordAll()[0]);
});


test('A missing record is returned as null', function () {
	Assert::null((new ArrayFluent([]))->record());
	Assert::same([], (new ArrayFluent([]))->recordAll());
});


test('Limit and offset keep the fluent for fetching records', function () {
	$fluent = new ArrayFluent([['id' => 1]]);
	Assert::same($fluent, $fluent->limit(10)->offset(20));
	Assert::count(1, $fluent->limit(10)->offset(20)->recordAll());
});
