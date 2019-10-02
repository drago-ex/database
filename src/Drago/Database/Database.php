<?php

declare(strict_types = 1);

/**
 * Drago Database
 * Package built on Nette Framework
 */
namespace Drago\Database;

use Dibi\Connection;
use Nette\SmartObject;


/**
 * Database connection.
 */
class Database
{
	use SmartObject;

	/** @var Connection */
	protected $db;


	public function __construct(Connection $db)
	{
		$this->db = $db;
	}
}
