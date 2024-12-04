<?php

namespace Windsor\Phetl\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Windsor\Phetl\Phetl
 */
class Phetl extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Windsor\Phetl\Phetl::class;
    }
}
