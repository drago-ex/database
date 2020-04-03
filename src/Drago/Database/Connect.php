<?php

declare(strict_types = 1);

/**
 * Drago Extension
 * Package built on Nette Framework
 */

namespace Drago\Database;

use Dibi\Connection;
use Nette;


/**
 * Database connection.
 */
class Connect
{
	use Nette\SmartObject;

	/** @var Connection */
	protected $db;


	public function __construct(Connection $db)
	{
		$this->db = $db;
	}
}
