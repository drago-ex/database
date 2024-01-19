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
	$table = repository()->getTableName();

	Assert::same('test_repository', $table);
});


test('Get table column primary key', function () {
	$primaryKey = repository()->getPrimaryKey();

	Assert::same('id', $primaryKey);
});


test('Get all records', function () {
	$row = repository()->table();

	Assert::type(Fluent::class, $row);
});


test('Find a record by parameter', function () {
	$row = repository()->table('sample', 'Hello')->fetch();

	Assert::same('Hello', $row['sample']);
});


test('Get records by table name', function () {
	$row = repository()->of('test_entity');

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
