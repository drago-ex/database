<?php

declare(strict_types=1);

namespace Drago\Database;

use Dibi\Row;


class Entity extends Row
{
	public function __construct(array $arr = [])
	{
		parent::__construct($arr);
	}
}
