<?php

declare(strict_types=1);


class TestEntityOracle extends Drago\Database\EntityOracle
{
	public const Table = 'table';
	public const Id = 'id';
	public const Sample = 'sample';

	public int $id;
	public string $sample;
}
