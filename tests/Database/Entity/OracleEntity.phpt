<?php

declare(strict_types = 1);

namespace Test\Database;

use Examples\SampleOracleEntity;
use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';
require __DIR__ . '/../../../examples/SampleOracleEntity.php';


test(function () {
	$class = new SampleOracleEntity([
		'sample_id' => 1,
		'sample_string' => 'Hello',
	]);

	Assert::same(1, $class->getSampleId());
	Assert::same('Hello', $class->getSampleString());

	Assert::equal([
		SampleOracleEntity::SAMPLE_ID => 1,
		SampleOracleEntity::SAMPLE_STRING => 'Hello',
	], $class->toArray());
});


test(function () {
	$entity = new SampleOracleEntity;
	$entity->setSampleId(0);
	$entity->setSampleString('Entity');

	Assert::same(0, $entity->getSampleId());
	Assert::same('Entity', $entity->getSampleString());
	Assert::equal([
		strtoupper(SampleOracleEntity::SAMPLE_ID) => 0,
		strtoupper(SampleOracleEntity::SAMPLE_STRING) => 'Entity',
	], $entity->getModify());
});


test(function () {
	$class = new SampleOracleEntity([
		'sample_id' => 99,
		'sample_string' => 'Hello',
	]);
	Assert::same(4, $class->count());
	Assert::same(99, $class->offsetGet(SampleOracleEntity::SAMPLE_ID));
	Assert::true($class->offsetExists(SampleOracleEntity::SAMPLE_ID));
	Assert::same(SampleOracleEntity::SAMPLE_ID, $class->getIterator()->key());

	$class->offsetUnset(SampleOracleEntity::SAMPLE_ID);
});
