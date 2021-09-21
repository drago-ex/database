<?php

declare(strict_types=1);


class TestEntity extends Drago\Database\Entity
{
	public const TABLE = 'test_entity';
	public const PRIMARY = 'id';
	public const SAMPLE = 'sample';

	public int $id;
	public string $sample;
}
