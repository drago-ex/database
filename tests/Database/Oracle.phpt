<?php declare(strict_types=1);

/**
 * Test: Drago\Database\EntityOracle
 */

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test('Inserting data into an entity', function () {
	$entity = new TestEntityOracle;
	$entity->id = 1;
	$entity->sample = 'sample';
	$entity->sampleNote = 'note';

	Assert::same([
		'ID' => 1,
		'SAMPLE' => 'sample',
		'SAMPLE_NOTE' => 'note',
	], $entity->toColumns());
});


test('Extract data from an entity', function () {
	$arr = [
		'ID' => 1,
		'SAMPLE' => 'sample',
		'SAMPLE_NOTE' => 'note',
	];
	$entity = new TestEntityOracle($arr);

	Assert::same(1, $entity->id);
	Assert::same('sample', $entity->sample);
	Assert::same('note', $entity->sampleNote);
	Assert::same([
		'id' => 1,
		'sample' => 'sample',
		'sampleNote' => 'note',
	], $entity->toArray());
});
