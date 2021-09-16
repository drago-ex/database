<?php

declare(strict_types = 1);

#[\Drago\Attr\Table(TestEntity::TABLE, TestEntity::PRIMARY)]
class TestRepository extends Drago\Database\Connect
{
}
