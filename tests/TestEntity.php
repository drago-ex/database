<?php

declare(strict_types=1);


class TestEntity extends Drago\Database\Entity
{
	public const Table = 'test_entity';
	public const PrimaryKey = 'id';
	public const ColumnSample = 'sample';

	public ?int $id = null;
	public string $sample;
}
