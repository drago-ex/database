<?php

declare(strict_types = 1);

use Drago\Database\Connect;
use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';


function connect(): Connect
{
	$db = new Database;
	return new Connect($db->connection());
}


test(function () {
	$row = connect()->db->isConnected();
	Assert::same('true', $row);
});
