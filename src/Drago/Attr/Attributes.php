<?php

declare(strict_types=1);

namespace Drago\Attr;

use Dibi\Row;


readonly class Attributes
{
	/** @param class-string<Row>|null $entity */
	public function __construct(
		public string $name,
		public ?string $primaryKey = null,
		public ?string $entity = null,
	) {
	}
}
