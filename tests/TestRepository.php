<?php

declare(strict_types=1);

use Dibi\Connection;
use Drago\Attr\Table;
use Drago\Database\Repository;


#[Table('test_repository', 'id')]
class TestRepository
{
	use Repository;

	public function __construct(
		public Connection $db,
	) {
	}
}
