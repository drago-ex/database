<?php

declare(strict_types=1);

use Dibi\Connection;
use Drago\Attr\From;
use Drago\Database\Query;


#[From('test_repository', 'id')]
class TestQuery
{
	use Query;

	public function __construct(
		public Connection $db,
	) {
	}
}
