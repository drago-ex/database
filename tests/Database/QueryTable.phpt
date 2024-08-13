<?php

/**
 * Test: Drago\Database\QueryTable
 */

declare(strict_types=1);

use Dibi\Fluent;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


function query(): TestQueryTable
{
	$db = new Database;
	return new TestQueryTable($db->connection());
}


test('Get table name', function () {
	$table = query()->getTableName();

	Assert::same('test_repository', $table);
});


test('Get table column primary key', function () {
	$id = query()->getPrimaryKey();

	Assert::same('id', $id);
});


test('Get all records', function () {
	$row = query()->table();

	Assert::type(Fluent::class, $row);
});


test('Find a record by parameter', function () {
	$row = query()->table('sample = ?', 'Hello')->fetch();

	Assert::same('Hello', $row['sample']);
});


test('Get record by id', function () {
	$row = query()->get(1)->fetch();

	Assert::equal([
		'id' => 1,
		'sample' => 'Hello',
	], $row->toArray());
});


test('Insert record', function () {
	$data = [
		'sample' => 'Insert',
	];
	$repository = query();
	$repository->put($data);

	Assert::same(2, $repository->getInsertId());
});


test('Get and update record', function () {
	$row = query()->get(2)->fetch();
	$row['sample'] = 'Update';
	query()->put($row->toArray());

	Assert::same('Update', $row['sample']);
});


test('Find a records by ids', function () {
	$rows = query()->table('id IN (?)', [1, 2])->fetchAll();

	Assert::type('array', $rows);
});


test('Delete record', function () {
	query()->remove(2)->execute();
	$row = query()->get(2)->fetch();

	Assert::null($row);
});
