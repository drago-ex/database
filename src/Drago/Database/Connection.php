<?php

/**
 * This file is part of the Drago Framework
 * Copyright (c) 2015, Zdeněk Papučík
 */
namespace Drago\Database;
use Nette;

/**
 * Connect to database.
 * @author Zdeněk Papučík
 */
abstract class Connection extends Nette\Object
{
	/**
	 * Database connect.
	 * @var \DibiConnection
	 */
	protected $db;

	public function __construct(\DibiConnection $db)
	{
		$this->db = $db;
	}

}
