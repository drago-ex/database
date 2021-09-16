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
	/** Table name. */
	public string $table;

	/** Table primary key. */
	public string $primary;


	public function __construct(string $table, string $primary)
	{
		$this->table = $table;
		$this->primary = $primary;
	}
}