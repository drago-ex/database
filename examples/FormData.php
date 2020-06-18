<?php

declare(strict_types = 1);

namespace Examples;

use Drago\Utils\ExtraArrayHash;


class FormData extends ExtraArrayHash
{
	public const SAMPLE_ID = 'sampleId';
	public const SAMPLE_STRING = 'sampleString';

	/** @var int */
	public $sampleId;

	/** @var string */
	public $sampleString;
}
