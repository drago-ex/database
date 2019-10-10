<?php

declare(strict_types = 1);

namespace Test\Database;

use Examples\SampleEntity;
use Examples\SampleRepository;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';
require __DIR__ . '/../../examples/SampleEntity.php';
require __DIR__ . '/../../examples/SampleRepository.php';


test(function () {
	$repository = new SampleRepository(connect());
	$find = $repository->find(1);

	Assert::same(1, $find->getSampleId());
	Assert::same('Hello', $find->getSampleString());

	Assert::equal([
		SampleEntity::SAMPLE_ID => 1,
		SampleEntity::SAMPLE_STRING => 'Hello',
	], $find->toArray());
});


test(function () {
	$repository = new SampleRepository(connect());

	$entity = new SampleEntity;
	$entity->setSampleId(0);
	$entity->setSampleString('Insert');

	Assert::equal([
		SampleEntity::SAMPLE_ID => 0,
		SampleEntity::SAMPLE_STRING => 'Insert',
	], $entity->getModify());

	$repository->save($entity);
	Assert::same(2, $repository->getInsertedId());

	$find = $repository->find(2);
	Assert::same('Insert', $find->getSampleString());
});


test(function () {
	$repository = new SampleRepository(connect());

	$entity = new SampleEntity;
	$entity->setSampleId(2);
	$entity->setSampleString('Modify');

	Assert::equal([
		SampleEntity::SAMPLE_ID => 2,
		SampleEntity::SAMPLE_STRING => 'Modify',
	], $entity->getModify());

	$repository->save($entity);

	$find = $repository->find(2);
	Assert::same(2, $find->getSampleId());
	Assert::same('Modify', $find->getSampleString());
});


test(function () {
	$repository = new SampleRepository(connect());
	$greeting = $repository->find(1);
	$greeting->setSampleString('Hello, World!');
	$repository->save($greeting);

	Assert::same('Hello, World!', $greeting->getSampleString());
});


test(function () {
	$repository = new SampleRepository(connect());
	$repository->eraseId(2);
});
