<?php declare(strict_types=1);

use Dibi\Connection;
use Drago\Attr\Table;
use Drago\Database\Database;


#[Table(TestEntityTyped::Table, TestEntityTyped::PrimaryKey, entity: TestEntityTyped::class)]
class TestDatabaseTyped
{
	/** @use Database<TestEntityTyped> */
	use Database;

	public function __construct(
		private readonly Connection $connection,
	) {
	}
}
