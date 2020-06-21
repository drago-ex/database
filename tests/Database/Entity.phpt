<?php

declare(strict_types = 1);

use Dibi\Result;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


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
 * @return array|TestEntity|null
 * @throws Dibi\Exception
 */
function find(int $id)
{
	return repository()->discoverId($id)
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

	Assert::same(1, $row->sampleId);
	Assert::same('Hello', $row->sampleString);
});


test(function () {
	$entity = new TestEntity;
	$entity->sampleString = 'Insert';

	save($entity);
	$row = find(3);

	Assert::same(3, $row->sampleId);
	Assert::same('Insert', $row->sampleString);
});


test(function () {
	$row = find(3);
	$row->sampleString = 'Update';

	save($row);

	Assert::same(3, $row->sampleId);
	Assert::same('Update', $row->sampleString);
});
