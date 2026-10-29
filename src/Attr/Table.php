<?php declare(strict_types=1);

namespace Drago\Attr;

use Attribute;
use Dibi\Row;


#[Attribute(Attribute::TARGET_CLASS)]
readonly class Table
{
	public function __construct(
		public string $name,
		public ?string $primaryKey = null,
		/** @var class-string<Row>|null */
		public ?string $entity = null,
	) {
	}
}
