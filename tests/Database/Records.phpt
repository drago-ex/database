<?php

/**
 * Test: Drago\Database\Records
 */

declare(strict_types=1);

use Dibi\Result;
use Dibi\Row;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


function records(): TestRecords
{
	$db = new Database;
	return new TestRecords($db->connection());
}


function search(int $id): array|TestEntity|Row|null
{
	return records()->one($id);
}


function save(TestEntity $entity): Result|int|null
{
	return records()->put($entity);
}


test('Find record by id', function () {
	$row = search(1);

	Assert::same(1, $row->id);
	Assert::same('Hello', $row->sample);
});


test('Find record by column name', function () {
	$row = records()->find('sample = ?', 'Hello');

	Assert::same('Hello', $row->sample);
});


test('Insert a record with an entity', function () {
	$entity = new TestEntity;
	$entity->sample = 'Insert';

	save($entity);
	$row = search(2);

	Assert::same(2, $row->id);
	Assert::same('Insert', $row->sample);
});


test('Update the record with the entity', function () {
	$row = search(2);
	$row->sample = 'Update';

	save($row);

	Assert::same(2, $row->id);
	Assert::same('Update', $row->sample);
});
