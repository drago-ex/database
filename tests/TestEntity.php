<?php

declare(strict_types=1);


class TestEntity extends Drago\Database\Entity
{
	const TABLE = 'test';
	const PRIMARY = 'id';

	public int $id;
	public string $sample;
}
