<?php

declare(strict_types=1);

use Tester\Environment;

require __DIR__ . '/../vendor/autoload.php';

Environment::setup();
date_default_timezone_set('Europe/Prague');


function test(string $title, Closure $function): void
{
	$function();
}
