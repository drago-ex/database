<?php

/**
 * This file is part of the Drago Framework
 * Copyright (c) 2015, Zdeněk Papučík
 */
namespace Drago\Database;

use Nette;
use Dibi;

/**
 * Connect to database.
 * @author Zdeněk Papučík
 */
abstract class Connection extends Nette\Object
{
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
