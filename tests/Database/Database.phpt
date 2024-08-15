<?php

/**
 * Test: Drago\Database\QueryRowClass
 */

declare(strict_types=1);

use Dibi\Result;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


function database(): TestDatabase
{
	$db = new Database;
	return new TestDatabase($db->connection());
}


function find(int $id): TestEntity|null
{
	return database()->find(TestEntity::PrimaryKey, $id)->record();
}


function save(TestEntity $entity): Result|int|null
{
	return database()->save($entity);
}


test('Find record by id', function () {
	$row = find(1);

	Assert::same(1, $row->id);
	Assert::same('Hello', $row->sample);
});


test('Find all records', function () {
	$row = database()->read()->recordAll();

	Assert::type('array', $row);
});


test('Find record by column name', function () {
	$row = database()->find('sample', 'hello')->record();

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


test('Get table name', function () {
	$table = database()->getTableName();

	Assert::same('test', $table);
});


test('Get table column primary key', function () {
	$primaryKey = database()->getPrimaryKey();

	Assert::same('id', $primaryKey);
});


test('Get class name', function () {
	$className = database()->getClassName();

	Assert::same(TestEntity::class, $className);
});


test('Delete record by where', function () {
	database()->delete()->where('id = ?', 1)->execute();
	$row = find(1);

	Assert::null($row);
});


test('Delete record by id', function () {
	database()->delete(2)->execute();
	$row = find(2);

	Assert::null($row);
});
