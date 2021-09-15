<?php

/**
 * Drago Extension
 * Package built on Nette Framework
 */

namespace Drago\Attr;


/**
 * Retrieving attributes from the repository.
 */
trait Attributes
{
    /**
     * Parse Repository attributes table and primary key.
     */
    public function attributes(): array
    {
        $reflection = new \ReflectionClass(get_class($this));
        $attrs = $reflection->getAttributes();

        $arr = [];
        foreach ($attrs as $attr) {
            $arr = $attr->getArguments();
        }
        return $arr;
    }
}
