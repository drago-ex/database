<?php

declare(strict_types = 1);

namespace Test\Database;

use Dibi\Connection;
use Examples\Oracle\Entity;
use Examples\Oracle\Repository;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';
require __DIR__ . '/../../examples/oracle/Entity.php';
require __DIR__ . '/../../examples/oracle/Repository.php';


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


test(function () {
	$repository = new Repository(connect());
	$repository->put(['SAMPLE_ID' => null, 'SAMPLE_STRING' => 'Hello']);
	$find = $repository->find(1);

	Assert::same(1, $find->getSampleId());
	Assert::same('Hello', $find->getSampleString());

	Assert::equal([
		Entity::SAMPLE_ID => 1,
		Entity::SAMPLE_STRING => 'Hello',
	], $find->toArray());
});


test(function () {
	$repository = new Repository(connect());

	$entity = new Entity;
	$entity->setSampleString('Insert');

	Assert::equal([
		strtoupper(Entity::SAMPLE_STRING) => 'Insert',
	], $entity->getModify());

	$repository->save($entity);
	Assert::same(2, $repository->getInsertedId('TEST_SEQ'));

	$find = $repository->find(2);
	Assert::same('Insert', $find->getSampleString());
});


test(function () {
	$repository = new Repository(connect());

	$entity = new Entity;
	$entity->setSampleId(2);
	$entity->setSampleString('Modify');

	Assert::equal([
		strtoupper(Entity::SAMPLE_ID) => 2,
		strtoupper(Entity::SAMPLE_STRING) => 'Modify',
	], $entity->getModify());

	$repository->save($entity);

	$find = $repository->find(2);
	Assert::same(2, $find->getSampleId());
	Assert::same('Modify', $find->getSampleString());
});


test(function () {
	$repository = new Repository(connect());
	$greeting = $repository->find(1);
	$greeting->setSampleString('Hello, World!');
	$repository->save($greeting);

	Assert::same('Hello, World!', $greeting->getSampleString());
});


test(function () {
	$repository = new Repository(connect());
	$repository->eraseId(2);
});
