<?php

/**
 * Test: Drago\Database\Repository
 */

declare(strict_types=1);

use Dibi\Fluent;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


function repository(): TestRepository
{
	$db = new Database;
	return new TestRepository($db->connection());
}


test('Get table name', function () {
	$table = repository()->getTable();

	Assert::same('test_repository', $table);
});


test('Get table column primary key', function () {
	$id = repository()->getPrimaryKey();

	Assert::same('id', $id);
});


test('Get all records', function () {
	$row = repository()->query();

	Assert::type(Fluent::class, $row);
});


test('Find a record by parameter', function () {
	$row = repository()->query('sample', 'Hello')->fetch();

	Assert::same('Hello', $row['sample']);
});


test('Get records by table name', function () {
	$row = repository()->queryOf('test_entity');

	Assert::type(Fluent::class, $row);
});


test('Get record by id', function () {
	$row = repository()->get(1)->fetch();

	Assert::equal([
		'id' => 1,
		'sample' => 'Hello',
	], $row->toArray());
});


test('Insert record', function () {
	$data = [
		'sample' => 'Insert',
	];
	$repository = repository();
	$repository->put($data);

	Assert::same(2, $repository->getInsertId());
});


test('Get and update record', function () {
	$row = repository()->get(2)->fetch();
	$row['sample'] = 'Update';
	repository()->put($row->toArray());

	Assert::same('Update', $row['sample']);
});


test('Delete record', function () {
	repository()->remove(2);
	$row = repository()->get(2)->fetch();

	Assert::null($row);
});
