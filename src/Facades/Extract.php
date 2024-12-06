<?php

namespace Windsor\Phetl\Facades;

use Illuminate\Support\Facades\Facade;
use Windsor\Phetl\ExtractorBuilder;

/**
 * @see \Windsor\Phetl\ExtractorBuilder
 */
class Extract extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return ExtractorBuilder::class;
    }
}
