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
		'sampleId' => 1,
		'sampleString' => 'Hello',
	], $row->toArray());
});


test(function () {
	$data = [
		'sampleString' => 'Insert',
	];
	$repository = repository();
	$repository->put($data);

	Assert::same(2, $repository->getInsertId());
});


test(function () {
	$row = repository()->get(2)->fetch();
	$row['sampleString'] = 'Update';
	repository()->put($row->toArray());

	Assert::same('Update', $row['sampleString']);
});


test(function () {
	repository()->erase(2);
	$row = repository()->get(2)->fetch();

	Assert::null($row);
});
