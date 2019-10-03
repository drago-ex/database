<?php

declare(strict_types = 1);

namespace Examples;

use Drago\Database\Entity;

class SampleEntity extends Entity
{
	public const TABLE = 'table';
	public const SAMPLE_ID = 'sampleId';
	public const SAMPLE_STRING = 'sampleString';

	/** @var int */
	public $sampleId;

	/** @var string */
	public $sampleString;


	public function setSampleId(int $sampleId)
	{
		$this['sampleId'] = $sampleId;
	}


	public function getSampleId(): int
	{
		return $this->sampleId;
	}


	public function setSampleString(string $sampleString)
	{
		$this['sampleString'] = $sampleString;
	}


	public function getSampleString(): string
	{
		return $this->sampleString;
	}
}
