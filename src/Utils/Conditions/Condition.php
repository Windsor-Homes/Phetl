<?php

namespace Windsor\Phetl\Utils\Conditions;

abstract class Condition
{
    abstract public function check($row): bool;

    public function __get($name)
    {
        return $this->{$name};
    }
}