<?php

declare(strict_types = 1);


class TestRepository extends Drago\Database\Connect
{
	use Drago\Database\Repository;

	public string $table = 'test';
	public string $primary = 'sampleId';
}
