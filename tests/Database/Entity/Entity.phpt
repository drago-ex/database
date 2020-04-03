<?php

declare(strict_types = 1);

use Examples\Entity;
use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';


function repository(): Mysql
{
	$db = new Connect;
	return new Mysql($db->mysql());
}


function entity(): Entity
{
	return new Entity;
}


test(function () {
	$row = repository()->find(1);

	Assert::same(1, $row->getSampleId());
	Assert::same('Hello', $row->getSampleString());
	Assert::equal([
		Entity::SAMPLE_ID => 1,
		Entity::SAMPLE_STRING => 'Hello',
	], $row->toArray());
});


test(function () {
	$entity = entity();
	$entity->setSampleString('Insert');

	Assert::equal([
		Entity::SAMPLE_STRING => 'Insert',
	], $entity->getModify());

	$repository = repository();
	$repository->save($entity);

	Assert::same(2, $repository->getInsertedId());

	$row = repository()->find(2);
	Assert::same('Insert', $row->getSampleString());
});


test(function () {
	$entity = entity();
	$entity->setSampleId(2);
	$entity->setSampleString('Modify');
	repository()->save($entity);

	$row = repository()->find(2);
	Assert::same(2, $row->getSampleId());
	Assert::same('Modify', $row->getSampleString());
});


test(function () {
	$row = repository()->find(1);
	$row->setSampleString('Hello, World!');
	repository()->save($row);

	Assert::same('Hello, World!', $row->getSampleString());
});


test(function () {
	repository()->eraseId(2);
	$row = repository()->find(2);

	Assert::null($row);
});


test(function () {
	$row = repository()->find(1);

	Assert::same(4, $row->count());
	Assert::same(1, $row->offsetGet(Entity::SAMPLE_ID));
	Assert::same(Entity::SAMPLE_ID, $row->getIterator()->key());
	Assert::true($row->offsetExists(Entity::SAMPLE_ID));

	$row->offsetUnset(Entity::SAMPLE_ID);
});
