<?php

declare(strict_types = 1);

namespace Examples;

use Nette\Utils\ArrayHash;


class FormData extends ArrayHash
{
	public const SAMPLE_ID = 'sample_id';
	public const SAMPLE_STRING = 'sample_string';

	/** @var int */
	public $sample_id;

	/** @var string */
	public $sample_string;
}
