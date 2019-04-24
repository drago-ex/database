<?php 

declare(strict_types = 1);

/**
 * Drago Database
 * @copyright Zdeněk Papučík
 */
namespace Drago\Database;

use Nette;
use Dibi;

/**
 * Database connection.
 * @package Drago\Database
 */
class Connection
{
	/** @var Dibi\Connection */
	protected $db;


	public function __construct(Dibi\Connection $db)
	{
		$this->db = $db;
	}
}
