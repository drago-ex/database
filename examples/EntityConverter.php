<?php

declare(strict_types = 1);

namespace Examples;

use Drago;


class EntityConverter extends Drago\Database\EntityConverter
{
	public const TABLE = 'TEST';
	public const SAMPLE_ID = 'sample_id';
	public const SAMPLE_STRING = 'sample_string';

	public ?int $sample_id;
	public string $sample_string;
}
