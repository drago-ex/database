<?php

declare(strict_types=1);

use Dibi\Result;
use Dibi\Row;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


/**
 * @throws \Dibi\Exception
 */
function repository(): TestRepository
{
	$db = new Database;
	return new TestRepository($db->connection());
}


function entity(): TestEntity
{
	return new TestEntity;
}


/**
 * @throws Dibi\Exception
 */
function find(int $id): array|TestEntity|Row|null
{
	return repository()->get($id)->execute()
		->setRowClass(TestEntity::class)
		->fetch();
}


/**
 * @throws Dibi\Exception
 */
function save(TestEntity $entity): Result|int|null
{
	return repository()->put($entity->toArray());
}


test(function () {
	$row = find(1);

	Assert::same(1, $row->id);
	Assert::same('Hello', $row->sample);
});


test(function () {
	$entity = new TestEntity;
	$entity->sample = 'Insert';

	save($entity);
	$row = find(2);

	Assert::same(2, $row->id);
	Assert::same('Insert', $row->sample);
});


test(function () {
	$row = find(2);
	$row->sample = 'Update';

	save($row);

	Assert::same(2, $row->id);
	Assert::same('Update', $row->sample);
});
