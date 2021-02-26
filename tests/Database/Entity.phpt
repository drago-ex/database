<?php

declare(strict_types = 1);

use Dibi\Result;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


/**
 * @throws \Dibi\Exception
 */
function repository(): TestRepository
{
	$db = new Database;
	$repository = new TestRepository($db->connection());
	$repository->table = 'test';
	return $repository;
}


function entity(): TestEntity
{
	return new TestEntity;
}


/**
 * @return array|TestEntity|null
 * @throws Dibi\Exception
 */
function find(int $id)
{
	return repository()->get($id)->execute()
		->setRowClass(TestEntity::class)
		->fetch();
}


/**
 * @return Result|int|null
 * @throws Dibi\Exception
 */
function save(TestEntity $entity)
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
