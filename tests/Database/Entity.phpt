<?php

/**
 * Test: Drago\Database\Entity
 */

declare(strict_types=1);

use Dibi\Result;
use Dibi\Row;
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


function find(int $id): array|TestEntity|Row|null
{
	return repository()->get($id)->execute()
		->setRowClass(TestEntity::class)
		->fetch();
}


function save(TestEntity $entity): Result|int|null
{
	return repository()->put($entity->toArray());
}


test('Find record by id', function () {
	$row = find(1);

	Assert::same(1, $row->id);
	Assert::same('Hello', $row->sample);
});
