<?php

declare(strict_types=1);

namespace Drago\Attr;


/** Class to represent table information such as name, primary key, and row class. */
class Attributes
{
	public function __construct(
		public string $name,
		public ?string $primaryKey = null,
		public ?string $class = null,
	) {
	}
}
