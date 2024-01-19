<?php

declare(strict_types=1);


class TestEntityOracle extends Drago\Database\EntityOracle
{
	public const TableName = 'table';
	public const PrimaryKey = 'id';
	public const ColumnSample = 'sample';

	public ?int $id = null;
	public string $sample;
}
