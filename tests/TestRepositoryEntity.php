<?php

declare(strict_types=1);

use Dibi\Connection;
use Drago\Attr\Table;


#[Table(TestEntity::TABLE, TestEntity::PRIMARY)]
class TestRepositoryEntity extends Drago\Database\Repository
{
	public function __construct(
		public Connection $db,
	) {
	}
}
