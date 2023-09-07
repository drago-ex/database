<?php

declare(strict_types=1);

/**
 * Drago Extension
 * Package built on Nette Framework
 */

namespace Drago\Attr;


/**
 * Get table information.
 */
class Attributes
{
	public function __construct(
		public string $table,
		public ?string $id = null,
	) {
	}
}
