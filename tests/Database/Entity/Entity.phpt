<?php

declare(strict_types = 1);

namespace Test\Entity;

use Dibi\Connection;
use Examples\Entity;
use Test\Repository\Mysql;
use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';
require __DIR__ . '/../../../examples/Entity.php';
require __DIR__ . '/../Repository/Mysql.php';


function connect(): Connection
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


function repository()
{
	$repository = new Mysql(connect());
	return $repository;
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
	$entity = new Entity;
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
	$entity = new Entity;
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
