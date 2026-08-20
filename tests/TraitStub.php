<?php

declare(strict_types=1);

namespace Drago\Database;

use Dibi\Connection;


/** TraitStub for PHPStan analysis of Database and AttributeDetection traits. */
class TraitStub
{
	/** @use Database<Entity> */
	use Database;

	public function __construct(
		protected Connection $connection,
	) {
	}
}
