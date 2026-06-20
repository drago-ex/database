<?php

declare(strict_types=1);

namespace Drago\Attr;


class Attributes
{
	public function __construct(
		public string $name,
		public ?string $primaryKey = null,
		public ?string $class = null,
	) {
	}
}
