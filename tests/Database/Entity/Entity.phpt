<?php

declare(strict_types = 1);

use Examples\Entity;
use Examples\FormData;
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


function formData(): FormData
{
	return new FormData;
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
	$repository->saveEntity($entity);

	Assert::same(2, $repository->getInsertedId());

	$row = repository()->find(2);
	Assert::same('Insert', $row->getSampleString());
});


test(function () {
	$entity = entity();
	$entity->setSampleId(2);
	$entity->setSampleString('Modify');
	repository()->saveEntity($entity);

	$row = repository()->find(2);
	Assert::same(2, $row->getSampleId());
	Assert::same('Modify', $row->getSampleString());
});


test(function () {
	$row = repository()->find(1);
	$row->setSampleString('Hello, World!');
	repository()->saveEntity($row);

	Assert::same('Hello, World!', $row->getSampleString());
});


test(function () {
	repository()->eraseId(2);
	$row = repository()->find(2);

	Assert::null($row);
});


test(function () {
	$data = formData();
	$data->sampleId = null;
	$data->sampleString = 'Insert';

	$repository = repository();
	$repository->saveFormData($data);

	$row = repository()->find(3);
	Assert::same('Insert', $row->getSampleString());
});


test(function () {
	$data = formData();
	$data->sampleId = 3;
	$data->sampleString = 'Modify';

	repository()->saveFormData($data);

	$row = repository()->find(3);
	Assert::same('Modify', $row->getSampleString());
});


test(function () {
	$row = repository()->find(1);

	Assert::same(4, $row->count());
	Assert::same(1, $row->offsetGet(Entity::SAMPLE_ID));
	Assert::same(Entity::SAMPLE_ID, $row->getIterator()->key());
	Assert::true($row->offsetExists(Entity::SAMPLE_ID));

	$row->offsetUnset(Entity::SAMPLE_ID);
});
