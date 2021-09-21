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