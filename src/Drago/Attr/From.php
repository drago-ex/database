<?php

declare(strict_types=1);

/**
 * Drago Extension
 * Package built on Nette Framework
 */

namespace Drago\Attr;

use Attribute;


#[Attribute(Attribute::TARGET_CLASS)]
class From
{
	public function __construct(
		public string $name,
		public ?string $primaryKey = null,
		public ?string $class = null,
	) {
	}
}
