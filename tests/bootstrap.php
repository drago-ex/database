<?php

declare(strict_types = 1);

use Dibi\Connection;

require __DIR__ . '/../vendor/autoload.php';

Tester\Environment::setup();


function config()
{
	try {
		$config = Tester\Environment::loadData();
	} catch (Exception $e) {
		$config = parse_ini_file(__DIR__ . '/databases.ini', true);
		$config = reset($config);
	}
	return $config;
}


function connect()
{
	return new Connection(config());
}


function test(\Closure $function): void
{
	$function();
}
