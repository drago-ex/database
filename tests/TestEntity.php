<?php

declare(strict_types=1);


class TestEntity extends Drago\Database\Entity
{
	public const table = 'test_entity';
	public const id = 'id';
	public const sample = 'sample';

	public int $id;
	public string $sample;
}
