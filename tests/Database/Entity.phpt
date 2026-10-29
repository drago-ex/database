<?php declare(strict_types=1);

/**
 * Test: Drago\Database\Entity
 */

use Drago\Database\Entity;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test('Snake case columns fill camel case properties', function () {
	$entity = new TestEntity([
		'id' => 1,
		'sample' => 'sample',
		'sample_note' => 'note',
	]);

	Assert::same(1, $entity->id);
	Assert::same('sample', $entity->sample);
	Assert::same('note', $entity->sampleNote);
});


test('Properties are returned under their column names', function () {
	$entity = new TestEntity;
	$entity->id = 1;
	$entity->sample = 'sample';
	$entity->sampleNote = 'note';

	Assert::same([
		'id' => 1,
		'sample' => 'sample',
		'sample_note' => 'note',
	], $entity->toColumns());
});


test('Uninitialized properties are left out of the columns', function () {
	$entity = new TestEntity;

	Assert::same([
		'id' => null,
		'sample_note' => null,
	], $entity->toColumns());
});


test('Camel case keys are converted to column names, column names stay', function () {
	Assert::same([
		'sample_note' => 'note',
		'used_count' => 1,
		'created_at' => null,
		'ID' => 2,
	], Entity::toColumnNames([
		'sampleNote' => 'note',
		'used_count' => 1,
		'createdAt' => null,
		'ID' => 2,
	]));
});


test('Properties named as the columns keep their names', function () {
	$entity = new TestEntitySnake([
		'id' => 1,
		'sample_note' => 'note',
	]);

	Assert::same('note', $entity->sample_note);
	Assert::same([
		'id' => 1,
		'sample_note' => 'note',
	], $entity->toColumns());
});
