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


function configOracle()
{
	try {
		$configOracle = Tester\Environment::loadData();
	} catch (Exception $e) {
		$configOracle = parse_ini_file(__DIR__ . '/databases.oracle.ini', true);
		$configOracle = reset($configOracle);
	}
	return $configOracle;
}


function connectOracle()
{
	return new Connection(configOracle());
}


function test(\Closure $function): void
{
	$function();
}
