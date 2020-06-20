<?php

declare(strict_types = 1);

namespace Examples;

use Drago;


class Entity extends Drago\Database\Entity
{
	public const TABLE = 'test';
	public const SAMPLE_ID = 'sampleId';
	public const SAMPLE_STRING = 'sampleString';

	public ?int $sampleId;
	public string $sampleString;
}
