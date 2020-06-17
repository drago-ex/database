<?php

declare(strict_types = 1);

namespace Examples;

use Nette\Utils\ArrayHash;


class FormData extends ArrayHash
{
	public const SAMPLE_ID = 'sampleId';
	public const SAMPLE_STRING = 'sampleString';

	/** @var int */
	public $sampleId;

	/** @var string */
	public $sampleString;
}
