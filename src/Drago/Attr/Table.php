<?php

/**
 * Drago Extension
 * Package built on Nette Framework
 */

namespace Drago\Attr;

use Attribute;


#[Attribute]
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
