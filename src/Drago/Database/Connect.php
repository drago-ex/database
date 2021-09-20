<?php

declare(strict_types=1);

/**
 * Drago Extension
 * Package built on Nette Framework
 */

namespace Drago\Database;

use Dibi\Connection;
use Drago\Attr\Attributes;
use Nette\SmartObject;


/**
 * Database connection.
 */
class Connect
{
	use Attributes;
	use Repository;
	use SmartObject;


	public function __construct(
		public Connection $db
	) {
	}
}
