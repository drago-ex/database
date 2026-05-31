<?php

declare(strict_types=1);

use Dibi\Connection;
use Drago\Attr\Table;
use Drago\Database\Database;


#[Table(TestEntity::Table, TestEntity::PrimaryKey, class: TestEntity::class)]
class TestDatabase
{
	/** @phpstan-use Database<TestEntity> */
	use Database;

	public function __construct(
		protected Connection $connection,
	) {
	}
}
