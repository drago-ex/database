<?php

declare(strict_types = 1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


function repository(): TestRepository
{
	$db = new Database;
	return new TestRepository($db->connection());
}


test(function () {
	$row = repository()->discoverId(1)->fetch();

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

	Assert::same(2, $repository->getInsertedId());
});


test(function () {
	$row = repository()->discoverId(2)->fetch();
	$row['sampleString'] = 'Update';
	repository()->put($row->toArray());

	Assert::same('Update', $row['sampleString']);
});


test(function () {
	repository()->eraseId(2);
	$row = repository()->discoverId(2)->fetch();

	Assert::null($row);
});
