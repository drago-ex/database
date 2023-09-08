<?php

declare(strict_types=1);


class TestEntity extends Drago\Database\Entity
{
	public const Table = 'test_entity';
	public const Id = 'id';
	public const Sample = 'sample';

	public int $id;
	public string $sample;
}
