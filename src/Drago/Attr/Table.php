<?php

declare(strict_types=1);

/**
 * Drago Extension
 * Package built on Nette Framework
 */

namespace Drago\Attr;

use Attribute;


/**
 * Attribute representing a table with its name and optional primary key.
 * This attribute is used to mark classes as corresponding to a database table.
 */
#[Attribute(Attribute::TARGET_CLASS)]
class Table
{
	/**
	 * @param string $name The name of the table.
	 * @param string|null $primaryKey The primary key of the table, if any.
	 */
	public function __construct(
		public string $name,
		public ?string $primaryKey = null,
	) {
	}
}
