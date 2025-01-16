<?php

namespace Windsor\Phetl\Facades;

use Illuminate\Support\Facades\Facade;
use Windsor\Phetl\Builders\LoadBuilder;

/**
 * @see \Windsor\Phetl\LoadBuilder
 */
class Load extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return LoadBuilder::class;
    }
}
