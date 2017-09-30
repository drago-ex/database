<?php

/**
 * Drago Database
 * Copyright (c) 2015, Zdeněk Papučík
 */
namespace Drago\Database;

use Nette;
use Dibi;

/**
 * Připojení k databázovému serveru.
 */
abstract class Connection
{
	use Nette\SmartObject;

	/**
	 * @var Dibi\Connection
	 */
	protected $db;

	public function __construct(Dibi\Connection $db)
	{
		$this->db = $db;
	}

}
