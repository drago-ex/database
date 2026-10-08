<?php

declare(strict_types=1);

namespace Drago\Attr;

use Attribute;
use Dibi\Row;


#[Attribute(Attribute::TARGET_CLASS)]
readonly class Table
{
	/**
	 * @param class-string<Row>|null $entity
	 * @param class-string<Row>|null $class
	 */
	public function __construct(
		public string $name,
		public ?string $primaryKey = null,
		public ?string $entity = null,
		public ?string $class = null,
	) {
	}
}
