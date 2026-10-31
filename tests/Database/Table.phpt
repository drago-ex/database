<?php declare(strict_types=1);

/**
 * Test: Drago\Database\Table
 */

use Drago\Database\Table;
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


/** @return Table<TestEntity> */
function database(): Table
{
	return new Table((new DatabaseConnection)->connection(), TestEntity::Table, TestEntity::PrimaryKey, TestEntity::class);
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


test('Find and delete records by more columns', function () {
	$database = database();
	$database->insert(['sample' => 'More', 'sampleNote' => 'One'])->execute();
	$database->insert(['sample' => 'More', 'sampleNote' => 'Two'])->execute();

	$row = $database->find(['sample' => 'More', 'sample_note' => 'Two'])->record();
	Assert::same('Two', $row->sampleNote);

	$database->delete(['sample' => 'More', 'sample_note' => 'One'])->execute();
	Assert::same(1, $database->count(['sample' => 'More']));
	$database->delete('sample', 'More')->execute();
});


test('Deleting with an empty condition is refused', function () {
	Assert::exception(
		fn() => database()->delete([]),
		InvalidArgumentException::class,
	);
});


test('Update a record with an entity', function () {
	$database = database();
	$database->insert(['sample' => 'Old'])->execute();
	$entity = $database->find('sample', 'Old')->record();
	$entity->sample = 'New';
	$database->update($entity)->where('%n = ?', 'id', $entity->id)->execute();

	Assert::same('New', $database->get($entity->id)->record()->sample);
	$database->delete('id', $entity->id)->execute();
});


test('Insert an entity', function () {
	$entity = new TestEntity;
	$entity->sample = 'Entity';
	$entity->sampleNote = 'Inserted';
	database()->insert($entity)->execute();
	$row = database()->find('sample', 'Entity')->record();

	Assert::same('Inserted', $row->sampleNote);
	database()->delete('sample', 'Entity')->execute();
});


test('A record is selected with LIMIT 1, a given limit is kept', function () {
	$database = database();
	$queries = [];
	$database->getConnection()->onEvent[] = function (Dibi\Event $event) use (&$queries): void {
		$queries[] = $event->sql;
	};

	$database->read('*')->record();
	$database->read('*')->limit(2)->record();
	$database->read('*')->recordAll();

	Assert::match('%a% LIMIT 1', $queries[0]);
	Assert::match('%a% LIMIT 2', $queries[1]);
	Assert::notContains('LIMIT', $queries[2]);
});


// #[\NoDiscard] works since PHP 8.5
if (PHP_VERSION_ID >= 80500) {
	test('A query builder that is thrown away is reported', function () {
		Assert::error(function () {
			database()->delete('sample', 'Missing');
		}, E_USER_WARNING, 'The return value of method %a%::delete() should either be used or intentionally ignored %a%');
	});
}


test('Count all records or those matching a condition', function () {
	Assert::same(2, database()->count());
	Assert::same(1, database()->count('%n = ?', 'sample', 'Hello'));
	Assert::same(0, database()->count(['sample' => 'Missing']));
});


test('A transaction commits after the callback and returns its result', function () {
	$database = database();
	$id = $database->transaction(function () use ($database): int {
		$database->insert(['sample' => 'Transaction'])->execute();
		return $database->getInsertId();
	});

	Assert::same('Transaction', $database->get($id)->record()->sample);
	$database->delete('id', $id)->execute();
});


test('A transaction rolls back and rethrows when the callback throws', function () {
	$database = database();
	Assert::exception(function () use ($database) {
		$database->transaction(function () use ($database) {
			$database->insert(['sample' => 'Rolled back'])->execute();
			throw new RuntimeException('Failed');
		});
	}, RuntimeException::class, 'Failed');

	Assert::same(0, $database->count(['sample' => 'Rolled back']));
});


test('Get table name', function () {
	Assert::same('test', database()->name);
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
	$database = new Table(
		(new DatabaseConnection)->connection(),
		TestEntityTyped::Table,
		TestEntityTyped::PrimaryKey,
		TestEntityTyped::class,
	);
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
