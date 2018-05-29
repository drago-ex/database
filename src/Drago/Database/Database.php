<?php

// Enable strict mode.
declare(strict_types = 1);

/**
 * Drago Database
 * Copyright (c) 2015, Zdeněk Papučík
 */
namespace Drago\Database;

use Nette;
use Dibi\Connection;

/**
 * Database connection.
 */
abstract class Database
{
	use Nette\SmartObject;

	/**
	 * @var Connection
	 */
	private $db;

	public function __construct(Connection $db)
	{
		$this->db = $db;
	}

	/**
	 * Building queries.
	 */
	public function db(): Connection
	{
		return $this->db;
	}

}
