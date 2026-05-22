<?php

declare(strict_types=1);

use Dibi\Connection;
use Drago\Attr\Table;
use Drago\Database\Database;
use Drago\Database\Entity;


#[Table(TestEntity::Table, TestEntity::PrimaryKey, class: TestEntity::class)]
class TestDatabase
{
	/** @use Database<Entity> */
	use Database;

	public function __construct(
		protected Connection $connection,
	) {
	}
}
