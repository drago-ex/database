<?php

class TestRepository extends Drago\Database\Connect
{
	use Drago\Database\Repository;

	public string $table = 'test';
	public string $columnId = 'sampleId';
}
