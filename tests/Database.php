<?php

declare(strict_types = 1);

use Dibi\Connection;
use Dibi\Exception;


class Database
{
	/**
	 * @throws Exception
	 */
	public function connection(): Connection
	{
		return new Connection([
			'driver' => 'mysqli',
			'host' => '127.0.0.1',
			'username' => 'accgit',
			'password' => 'root',
			'database' => 'nette',
		]);
	}
}
