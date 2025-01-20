<?php

namespace Windsor\Phetl\Utils\Conditions;

interface Condition
{
    public function check($row): bool;
}