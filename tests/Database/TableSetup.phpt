<?php declare(strict_types=1);

/**
 * Test: Drago\Database\Table setup
 */

use Dibi\Connection;
use Dibi\Row;
use Drago\Database\Table;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


function connection(): Connection
{
	return new Connection(['driver' => 'mysqli', 'lazy' => true]);
}


test('The table fetches records as its entity', function () {
	$table = new Table(connection(), TestEntity::Table, TestEntity::PrimaryKey, TestEntity::class);
	Assert::same('test', $table->name);
	Assert::same('id', $table->getPrimaryKey());
	Assert::same(TestEntity::class, $table->command()->className);
});


test('A table without an entity fetches Dibi rows', function () {
	$table = new Table(connection(), 'orders', 'id');
	Assert::same('orders', $table->name);
	Assert::same(Row::class, $table->entity);
	Assert::same(Row::class, $table->command()->className);
});


test('A table without a primary key works until a record is got or saved by it', function () {
	$table = new Table(connection(), 'items_view');
	Assert::null($table->primaryKey);
	Assert::exception(fn() => $table->get(1), LogicException::class, 'The table items_view has no primary key.');
	Assert::exception(fn() => $table->save(['name' => 'x']), LogicException::class, 'The table items_view has no primary key.');
});
