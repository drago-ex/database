<?php

declare(strict_types=1);

/**
 * Drago Extension
 * Package built on Nette Framework
 */

namespace Drago\Database;

use Dibi\Row;


/**
 * Base for entity.
 *
 * This class extends the Dibi\Row class, providing additional functionality
 * for working with database entities in the application.
 */
class Entity extends Row
{
	/**
	 * @param array<string, mixed> $arr The data to initialize the entity with.
	 */
	public function __construct(array $arr = [])
	{
		parent::__construct($arr);
	}
}
