<?php

declare(strict_types=1);

use Dibi\Connection;
use Drago\Attr\From;
use Drago\Database\QueryRowClass;


/** @extends TestEntity<TestEntity> */
#[From(TestEntity::Table, TestEntity::PrimaryKey, class: TestEntity::class)]
class TestQueryRowClass
{
	use QueryRowClass;

	public function __construct(
		public Connection $db,
	) {
	}
}
