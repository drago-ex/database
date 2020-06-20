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


test(function () {
	$row = repository()->find(1);

	Assert::same(1, $row->sample_id);
	Assert::same('Hello', $row->sample_string);
	Assert::equal([
		EntityConverter::SAMPLE_ID => 1,
		EntityConverter::SAMPLE_STRING => 'Hello',
	], $row->toArray());
});


test(function () {
	$entity = entity();
	$entity->sample_string = 'Insert';

	Assert::equal([
		strtoupper(EntityConverter::SAMPLE_STRING) => 'Insert',
	], $entity->getModify());

	$repository = repository();
	$repository->save($entity);
	Assert::same(2, $repository->getInsertedId('TEST_SEQ'));

	$row = repository()->find(2);
	Assert::same('Insert', $row->sample_string);
});


test(function () {
	$entity = entity();
	$entity->sample_id = 2;
	$entity->sample_string = 'Modify';

	repository()->save($entity);
	$find = repository()->find(2);

	Assert::same('Modify', $find->sample_string);
});


test(function () {
	$row = repository()->find(1);
	$row->sample_string = 'Hello, World!';
	repository()->save($row);

	Assert::same('Hello, World!', $row->sample_string);
});


test(function () {
	repository()->eraseId(2);
	$row = repository()->find(2);

	Assert::null($row);
});
