<?php declare(strict_types=1);

/**
 * Test: Drago\Database\ExtraFluent
 */

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


	public function __construct(
		/** @var list<array<string, mixed>> */
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
	public function __construct(
		/** @var list<array<string, mixed>> */
		private array $rows,
	) {
		parent::__construct(new Connection(['driver' => 'mysqli', 'lazy' => true]));
	}


	public function execute(?string $return = null): Result
	{
		return new Result(new ArrayResultDriver($this->rows), normalize: false);
	}


	/** @return array<mixed> */
	public function exportClause(string $clause): array
	{
		return $this->_export($clause);
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


test('Clauses keep the fluent for fetching records and reach the query', function () {
	$fluent = new ArrayFluent([['id' => 1]]);
	Assert::same($fluent, $fluent->select('*')->from('test')->leftJoin('other')->on('a = b')->limit(10)->offset(20));
	Assert::count(1, $fluent->recordAll());
	Assert::same(['LIMIT', 10], $fluent->exportClause('LIMIT'));
	Assert::same(['OFFSET', 20], $fluent->exportClause('OFFSET'));

	// Fluent::Remove removes a clause, e.g. the limit before counting
	Assert::same([], $fluent->limit(Dibi\Fluent::Remove)->exportClause('LIMIT'));
});
