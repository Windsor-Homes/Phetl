<?php

namespace Windsor\Phetl\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Windsor\Phetl\LoadBuilder
 */
class Load extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Windsor\Phetl\LoadBuilder::class;
    }
}
