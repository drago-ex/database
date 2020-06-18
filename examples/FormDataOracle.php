<?php

declare(strict_types = 1);

namespace Examples;

use Drago\Utils\ExtraArrayHash;


class FormDataOracle extends ExtraArrayHash
{
	public const SAMPLE_ID = 'sample_id';
	public const SAMPLE_STRING = 'sample_string';

	/** @var int */
	public $sample_id;

	/** @var string */
	public $sample_string;
}
