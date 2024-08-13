<?php

declare(strict_types=1);

use Dibi\Connection;
use Drago\Attr\From;
use Drago\Database\QueryTable;


#[From('test_repository', 'id')]
class TestQueryTable
{
	use QueryTable;

	public function __construct(
		public Connection $db,
	) {
	}
}
