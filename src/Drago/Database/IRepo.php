<?php

declare(strict_types = 1);

/**
 * Drago Extension
 * Package built on Nette Framework
 */

namespace Drago\Database;


interface IRepo
{
	/**
	 * Table name.
	 */
	public function table(): string;

	/**
	 * Column name of primary key.
	 */
	public function columnId(): string;
}
