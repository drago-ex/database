<?php

/**
 * Test: Drago\Database\Database
 */

declare(strict_types=1);

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


function database(): TestDatabase
{
	$db = new Database;
	return new TestDatabase($db->connection());
}


test('Get record by id', function () {
	$row = database()->get(1)->record();

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

	database()->save($entity);
	$row = database()->get(2)->record();

	Assert::same(2, $row->id);
	Assert::same('Insert', $row->sample);
});


test('Update the record with the entity', function () {
	$row = database()->get(2)->record();
	$row->sample = 'Update';

	database()->save($row);

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


test('Delete record by id', function () {
	database()->delete('id', 2)->execute();
	$row = database()->get(2)->record();

	Assert::null($row);
});
