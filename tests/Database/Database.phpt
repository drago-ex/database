<?php declare(strict_types=1);

/**
 * Test: Drago\Database\Database
 */

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
	$entity->sampleNote = 'Note';

	database()->save($entity);
	$row = database()
		->get(2)
		->record();

	Assert::same(2, $row->id);
	Assert::same('Insert', $row->sample);
	Assert::same('Note', $row->sampleNote);
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


test('Save camel case values into snake case columns', function () {
	database()->save(['id' => 2, 'sampleNote' => 'Values']);
	$row = database()
		->get(2)
		->record();

	Assert::same('Values', $row->sampleNote);
});


test('Insert camel case keys into snake case columns', function () {
	database()->insert(['sample' => 'Camel', 'sampleNote' => 'Inserted'])->execute();
	$row = database()->find('sample', 'Camel')->record();

	Assert::same('Inserted', $row->sampleNote);
	database()->delete('sample', 'Camel')->execute();
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


test('Bool and backed enum values are saved and read back', function () {
	$database = new TestDatabaseTyped((new DatabaseConnection)->connection());
	$entity = new TestEntityTyped;
	$entity->active = true;
	$entity->inMenu = false;
	$entity->status = TestStatus::Published;
	$entity->priority = TestPriority::High;
	$database->save($entity);

	$row = $database->find('status', TestStatus::Published)->record();
	Assert::type(TestEntityTyped::class, $row);
	Assert::true($row->active);
	Assert::false($row->inMenu);
	Assert::same(TestStatus::Published, $row->status);
	Assert::same(TestPriority::High, $row->priority);
	$database->delete('id', $row->id)->execute();
});
