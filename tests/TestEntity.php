<?php

declare(strict_types=1);


class TestEntity extends Drago\Database\Entity
{
	public const TABLE = 'test';
	public const PRIMARY = 'id';

	public ?int $id;
	public string $sample;
}
