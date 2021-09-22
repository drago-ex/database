<?php

declare(strict_types=1);


class TestEntityOracle extends Drago\Database\EntityOracle
{
	public const TABLE = 'table';
	public const PRIMARY = 'id';
	public const SAMPLE = 'sample';

	public int $id;
	public string $sample;
}
