<?php

/**
 * Test: Drago\Database\Entity
 */

declare(strict_types=1);

use Dibi\Result;
use Dibi\Row;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';

function repository(): TestRepositoryEntity
{
	$db = new Database;
	return new TestRepositoryEntity($db->connection());
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


test('Insert a record with an entity', function () {
	$entity = new TestEntity;
	$entity->sample = 'Insert';

	save($entity);
	$row = find(2);

	Assert::same(2, $row->id);
	Assert::same('Insert', $row->sample);
});


test('Update the record with the entity', function () {
	$row = find(2);
	$row->sample = 'Update';

	save($row);

	Assert::same(2, $row->id);
	Assert::same('Update', $row->sample);
});
