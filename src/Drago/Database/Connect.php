<?php

declare(strict_types = 1);

/**
 * Drago Extension
 * Package built on Nette Framework
 */

namespace Drago\Database;

use Dibi\Connection;
use Nette\SmartObject;


/**
 * Database connection.
 */
class Connect
{
	use SmartObject;

	/** @var Connection */
	protected $db;


	public function __construct(Connection $db)
	{
		$this->db = $db;
	}
}
