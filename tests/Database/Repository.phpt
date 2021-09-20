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


test(function () {
	$row = repository()->get(1)->fetch();

	Assert::equal([
		'id' => 1,
		'sample' => 'Hello',
	], $row->toArray());
});


test(function () {
	$data = [
		'id' => null,
		'sample' => 'Insert',
	];
	$repository = repository();
	$repository->put($data);

	Assert::same(2, $repository->getInsertId());
});


test(function () {
	$row = repository()->get(2)->fetch();
	$row['sample'] = 'Update';
	repository()->put($row->toArray());

	Assert::same('Update', $row['sample']);
});


test(function () {
	repository()->erase(2);
	$row = repository()->get(2)->fetch();

	Assert::null($row);
});
