<?php

/**
 * Test: Drago\Database\Repository
 */

declare(strict_types=1);

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';
require __DIR__ . '/../Database.php';
require __DIR__ . '/../TestRepository.php';
require __DIR__ . '/../TestEntity.php';


function repository(): TestRepository
{
	$db = new Database;
	return new TestRepository($db->connection());
}


test('Get table name', function () {
	$table = repository()->getTable();

	Assert::same('string', $table);
	Assert::same('test', $table);
});


test('Get table column primary key', function () {
	$priamry = repository()->getPrimary();

	Assert::same('string', $priamry);
	Assert::same('test', $priamry);
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
	repository()->erase(2);
	$row = repository()->get(2)->fetch();

	Assert::null($row);
});
