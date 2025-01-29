<?php

namespace Windsor\Phetl\Utils\Conditions;

abstract class Condition
{
    abstract public function check($row): bool;

    public function __get($name)
    {
        return $this->{$name};
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}