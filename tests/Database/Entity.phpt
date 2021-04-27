<?php

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


/**
 * @throws \Dibi\Exception
 */
function repository(): TestRepository
{
	$db = new Database;
	return new TestRepository($db->connection());
}


/**
 * @throws Dibi\Exception
 */
function find(int $id): array|TestEntity|null
{
	return repository()->get($id)->execute()
		->setRowClass(TestEntity::class)
		->fetch();
}


test(function () {
	$row = find(1);

	Assert::same(1, $row->id);
	Assert::same('Hello', $row->sample);
});


test(function () {
	$entity = new TestEntity;
	$entity->sample = 'Insert';

	Assert::same('Insert', $entity->sample);
});
