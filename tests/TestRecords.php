<?php

declare(strict_types=1);

use Dibi\Connection;
use Drago\Attr\From;
use Drago\Database\Records;


/** @extends TestRecords<TestEntity> */
#[From(TestEntity::Table, TestEntity::PrimaryKey, class: TestEntity::class)]
class TestRecords
{
	use Records;

	public function __construct(
		public Connection $db,
	) {
	}
}
