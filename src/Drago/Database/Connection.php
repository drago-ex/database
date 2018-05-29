<?php

// Enable strict mode.
declare(strict_types = 1);

/**
 * Drago Database
 * Copyright (c) 2015, Zdeněk Papučík
 */
namespace Drago\Database;

use Nette;
use Dibi;

/**
 * Database connection.
 */
abstract class Connection
{
	use Nette\SmartObject;

	/**
	 * @var Dibi\Connection
	 */
	private $db;

	public function __construct(Dibi\Connection $db)
	{
		$this->db = $db;
	}

	/**
	 * Building queries.
	 */
	public function db(): Dibi\Connection
	{
		return $this->db;
	}

}
