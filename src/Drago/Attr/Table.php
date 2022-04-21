<?php

declare(strict_types=1);

/**
 * Drago Extension
 * Package built on Nette Framework
 */

namespace Drago\Attr;

use Attribute;


#[Attribute(Attribute::TARGET_CLASS)]
class Table
{
	/** Attr name. */
	public string $table;

	/** Attr primary key. */
	public ?string $primary = null;


	public function __construct(string $table, ?string $primary = null)
	{
		$this->table = $table;
		$this->primary = $primary;
	}
}
