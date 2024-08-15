<?php

declare(strict_types=1);

use Drago\Attr\From;
use Drago\Database\Database;


/**
 * @extends Database<TestEntity>
 */
#[From(TestEntity::Table, TestEntity::PrimaryKey, class: TestEntity::class)]
class TestDatabase extends Database
{
}
