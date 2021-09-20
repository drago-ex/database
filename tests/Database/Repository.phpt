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
		'sample' => 'Insert',
	];
	repository()->put($data);
	$id = repository()->getInsertId();

	//Assert::same(2, $id);
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
