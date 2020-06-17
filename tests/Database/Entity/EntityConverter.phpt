<?php

declare(strict_types = 1);

use Examples\EntityConverter;
use Examples\FormDataOracle;
use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';


function repository(): Oracle
{
	$db = new Connect;
	return new Oracle($db->oracle());
}


function entity(): EntityConverter
{
	return new EntityConverter;
}


function formData(): FormDataOracle
{
	return new FormDataOracle;
}


test(function () {
	$row = repository()->find(1);

	Assert::same(1, $row->getSampleId());
	Assert::same('Hello', $row->getSampleString());
	Assert::equal([
		EntityConverter::SAMPLE_ID => 1,
		EntityConverter::SAMPLE_STRING => 'Hello',
	], $row->toArray());
});


test(function () {
	$entity = entity();
	$entity->setSampleString('Insert');

	Assert::equal([
		strtoupper(EntityConverter::SAMPLE_STRING) => 'Insert',
	], $entity->getModify());

	$repository = repository();
	$repository->saveEntity($entity);
	Assert::same(2, $repository->getInsertedId('TEST_SEQ'));

	$row = repository()->find(2);
	Assert::same('Insert', $row->getSampleString());
});


test(function () {
	$entity = entity();
	$entity->setSampleId(2);
	$entity->setSampleString('Modify');

	repository()->saveEntity($entity);
	$find = repository()->find(2);

	Assert::same('Modify', $find->getSampleString());
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
