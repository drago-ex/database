<?php

declare(strict_types=1);

/**
 * Drago Extension
 * Package built on Nette Framework
 */

namespace Drago\Database;

use Dibi\Row;


/**
 * Base for entity.
 */
class Entity extends Row
{
	public function __construct(array $arr = [])
	{
		parent::__construct($arr);
	}
}
