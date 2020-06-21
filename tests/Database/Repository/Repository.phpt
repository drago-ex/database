<?php

declare(strict_types = 1);

use Drago\Database\Connect;
use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';


class Repository extends Connect
{
	use Drago\Database\Repository;

	public string $table = 'test';
	public string $columnId = 'sampleId';
}


function repository(): Repository
{
	$db = new Database;
	return new Repository($db->connection());
}


test(function () {
	$row = repository()->discoverId(1)->fetch();

	Assert::equal([
		'sampleId' => 1,
		'sampleString' => 'Hello',
	], $row);
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
	$data = [
		'sampleId' => 2,
		'sampleString' => 'Update',
	];

	repository()->put($data, $data['sampleId']);
	$row = repository()->discoverId(2)->fetch();

	Assert::equal([
		'sampleId' => 2,
		'sampleString' => 'Update',
	], $row);
});


test(function () {
	$row = repository()->discoverId(1)->fetch();
	$row['sampleString'] = 'Hello, World!';
	repository()->put($row);

	Assert::same('Hello, World!', $row['sampleString']);
});


test(function () {
	repository()->eraseId(2);
	$row = repository()->discoverId(2)->fetch();

	Assert::null($row);
});
