<?php

/**
 * Drago Extension
 * Package built on Nette Framework
 */

declare(strict_types=1);

namespace Drago\Database;

use Dibi\Connection;
use Nette\SmartObject;


/**
 * Database connection.
 */
class Connect
{
	use SmartObject;

	public function __construct(
		public Connection $db
	) {
	}
}
