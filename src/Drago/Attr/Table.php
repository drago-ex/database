<?php

declare(strict_types=1);

/**
 * Drago Extension
 * Package built on Nette Framework
 */

namespace Drago\Attr;

use Attribute;


/**
 * Attribute to define table metadata for entities.
 *
 * This attribute is used to associate a class with a database table,
 * providing information such as the table name, primary key, and class name for rows.
 */
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
