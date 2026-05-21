<?php

declare(strict_types=1);


/** Test entity for database tests. */
class TestEntity extends Drago\Database\Entity
{
	public const string Table = 'test';
	public const string PrimaryKey = 'id';
	public const string ColumnSample = 'sample';

	public ?int $id = null;
	public string $sample;
}
