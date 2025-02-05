<?php

namespace Windsor\Phetl\Contracts;

interface Sequenceable
{
    public function transformRow($row);
}