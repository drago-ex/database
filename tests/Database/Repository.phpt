<?php

declare(strict_types = 1);

namespace Test\Database;

use Dibi\Connection;
use Examples\Mysql\Entity;
use Examples\Mysql\Repository;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';
require __DIR__ . '/../../examples/mysqli/Entity.php';
require __DIR__ . '/../../examples/mysqli/Repository.php';


function connect()
{
	$db = [
		'driver' => 'mysqli',
		'host' => '127.0.0.1',
		'username' => 'root',
		'password' => '',
		'database' => 'test',
	];
	return new Connection($db);
}


test(function () {
	$repository = new Repository(connect());
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
		Entity::SAMPLE_STRING => 'Insert',
	], $entity->getModify());

	$repository->save($entity);
	Assert::same(2, $repository->getInsertedId());

	$find = $repository->find(2);
	Assert::same('Insert', $find->getSampleString());
});


test(function () {
	$repository = new Repository(connect());

	$entity = new Entity;
	$entity->setSampleId(2);
	$entity->setSampleString('Modify');

	Assert::equal([
		Entity::SAMPLE_ID => 2,
		Entity::SAMPLE_STRING => 'Modify',
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

	$find = $repository->find(2);
	Assert::null($find);
});


test(function () {
	$repository = new Repository(connect());
	$find = $repository->find(1);

	Assert::same(4, $find->count());
	Assert::same(1, $find->offsetGet(Entity::SAMPLE_ID));
	Assert::true($find->offsetExists(Entity::SAMPLE_ID));
	Assert::same(Entity::SAMPLE_ID, $find->getIterator()->key());

	$find->offsetUnset(Entity::SAMPLE_ID);
});
