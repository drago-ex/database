<?php

/**
 * Extending for Nette Framework
 * Copyright (c) 2015, Zdeněk Papučík
 */
namespace Drago\Database;

use Nette;
use Dibi;

/**
 * Connect to database.
 * @author Zdeněk Papučík
 */
abstract class Connection
{
	use Nette\SmartObject;

	/**
	 * Database connect.
	 * @var Dibi\Connection
	 */
	protected $db;

	public function __construct(Dibi\Connection $db)
	{
		$this->db = $db;
	}

}
