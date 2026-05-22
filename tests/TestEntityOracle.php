<?php

declare(strict_types=1);


class TestEntityOracle extends Drago\Database\EntityOracle
{
	public const string Table = 'table';
	public const string PrimaryKey = 'id';
	public const string ColumnSample = 'sample';

	public ?int $id = null;
	public string $sample;
}
