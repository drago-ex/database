<?php

/**
 * Test: Drago\Database\Database
 */

declare(strict_types=1);

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';

try {
	(new DatabaseConnection)->connection();
} catch (Throwable $e) {
	// CI provides the database, so a failed connection there is an error, not a reason to skip.
	if (getenv('CI')) {
		throw $e;
	}

	Tester\Environment::skip('Database server is not available in local environment.');
}


function database(): TestDatabase
{
	$db = new DatabaseConnection;
	return new TestDatabase($db->connection());
}


test('Get record by id', function () {
	$row = database()
		->get(1)
		->record();

	Assert::same(1, $row->id);
	Assert::same('Hello', $row->sample);
});


test('Find all records', function () {
	$row = database()
		->read('*')
		->recordAll();

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
	$row = database()
		->get(2)
		->record();

	Assert::same(2, $row->id);
	Assert::same('Insert', $row->sample);
});


test('Update the record with the entity', function () {
	$row = database()
		->get(2)
		->record();

	$row->sample = 'Update';
	database()->save($row);

	Assert::same(2, $row->id);
	Assert::same('Update', $row->sample);
});


test('Get table name', function () {
	$table = database()
		->getTableName();

	Assert::same('test', $table);
});


test('Get table column primary key', function () {
	$primaryKey = database()
		->getPrimaryKey();

	Assert::same('id', $primaryKey);
});


test('Delete record by id', function () {
	database()->delete('id', 2)->execute();
	$row = database()
		->get(2)
		->record();

	Assert::null($row);
});
