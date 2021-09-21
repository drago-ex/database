<?php

/**
 * Test: Drago\Database\Connect
 */

declare(strict_types=1);

use Drago\Database\Connect;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


test('Database connection', function () {
	$db = new Database();
	$connect = new Connect($db->connection());

	Assert::type('bool', $connect->db->isConnected());
});
