<?php

declare(strict_types=1);

namespace Drago\Attr;

use Attribute;


/** Attribute to define table metadata for entities. */
#[Attribute(Attribute::TARGET_CLASS)]
readonly class Table
{
	public function __construct(
		public string $name,
		public ?string $primaryKey = null,
		public ?string $class = null,
	) {
	}
}
