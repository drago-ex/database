<?php

declare(strict_types=1);

use Drago\Attr\Table;


#[Table(TestEntity::TABLE, TestEntity::PRIMARY)]
class TestRepositoryEntity extends Drago\Database\Connect
{
}
