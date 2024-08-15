<?php

declare(strict_types=1);

use Drago\Attr\From;


/**
 * @extends \Drago\Database\Database<TestEntity>
 */
#[From(TestEntity::Table, TestEntity::PrimaryKey, class: TestEntity::class)]
class TestDatabase extends \Drago\Database\Database
{
}
