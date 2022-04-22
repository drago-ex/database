<?php

declare(strict_types=1);

use Dibi\Connection;
use Drago\Attr\Table;


#[Table('test_repository', 'id')]
class TestRepository extends Drago\Database\Repository
{
	public function __construct(
		public Connection $db,
	) {
	}
}
