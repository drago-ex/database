<?php

/**
 * Test: Drago\Database\EntityOracle
 */

declare(strict_types=1);

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


test('Inserting data into an entity', function () {
	$entity = new TestEntityOracle;
	$entity->id = 1;
	$entity->sample = 'sample';

	Assert::equal([
		'ID' => 1,
		'SAMPLE' => 'sample',
	], $entity->toArrayUpper());
});


test('Extract data from an entity', function () {
	$arr = [
		'ID' => 1,
		'SAMPLE' => 'sample',
	];
	$entity = new TestEntityOracle($arr);

	Assert::same(1, $entity->id);
	Assert::same('sample', $entity->sample);
	Assert::equal([
		'id' => 1,
		'sample' => 'sample',
	], $entity->toArray());
});
