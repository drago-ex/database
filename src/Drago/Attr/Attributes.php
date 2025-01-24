<?php

declare(strict_types=1);

/**
 * Drago Extension
 * Package built on Nette Framework
 */

namespace Drago\Attr;


/**
 * Represents the table information such as name and primary key.
 */
class Attributes
{
	public function __construct(
		public string $name,
		public ?string $primaryKey = null,
	) {
	}
}
