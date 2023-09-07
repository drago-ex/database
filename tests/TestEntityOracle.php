<?php

declare(strict_types=1);


class TestEntityOracle extends Drago\Database\EntityOracle
{
	public const table = 'table';
	public const id = 'id';
	public const sample = 'sample';

	public int $id;
	public string $sample;
}
