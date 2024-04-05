<?php

declare(strict_types=1);

/**
 * Drago Extension
 * Package built on Nette Framework
 */

namespace Drago\Database;

use Drago\Attr\AttributeDetection;


/**
 * Repository base.
 */
trait Repository
{
	use AttributeDetection;
	use Query;
}
