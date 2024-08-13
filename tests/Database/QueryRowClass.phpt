<?php

/**
 * Test: Drago\Database\QueryRowClass
 */

declare(strict_types=1);

use Dibi\Result;
use Dibi\Row;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


function records(): TestQueryRowClass
{
	$db = new Database;
	return new TestQueryRowClass($db->connection());
}


function search(int $id): array|TestEntity|Row|null
{
	return records()->fetch(records()->get(1));
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
	$row = records()->fetch(records()->table('sample = ?', 'Hello'));

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
