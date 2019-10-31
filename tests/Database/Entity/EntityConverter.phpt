<?php

declare(strict_types = 1);

namespace Test\Entity;

use Dibi\Connection;
use Examples\EntityConverter;
use Test\Repository\Oracle;
use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';
require __DIR__ . '/../../../examples/EntityConverter.php';
require __DIR__ . '/../Repository/Oracle.php';


function connect()
{
	$db = [
		'driver' => 'oracle',
		'username' => 'travis',
		'password' => 'travis',
		'database' => '(DESCRIPTION=(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = localhost)(PORT = 1521)))(CONNECT_DATA=(SID=xe)))',
		'charset' => 'utf8',
	];
	return new Connection($db);
}


function repository()
{
	$repository = new Oracle(connect());
	return $repository;
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
	$entity = new EntityConverter;
	$entity->setSampleString('Insert');

	Assert::equal([
		strtoupper(EntityConverter::SAMPLE_STRING) => 'Insert',
	], $entity->getModify());

	$repository = repository();
	$repository->save($entity);

	Assert::same(2, $repository->getInsertedId('TEST_SEQ'));

	$row = repository()->find(2);
	Assert::same('Insert', $row->getSampleString());
});


test(function () {
	$entity = new EntityConverter();
	$entity->setSampleId(2);
	$entity->setSampleString('Modify');

	repository()->save($entity);

	$find = repository()->find(2);
	Assert::same('Modify', $find->getSampleString());
});


test(function () {
	repository()->eraseId(2);
	$row = repository()->find(2);

	Assert::null($row);
});


test(function () {
	$row = repository()->find(1);
	$row->setSampleString('Hello, World!');
	repository()->save($row);

	Assert::same('Hello, World!', $row->getSampleString());
});
