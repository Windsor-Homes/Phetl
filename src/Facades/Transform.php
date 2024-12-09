<?php

namespace Windsor\Phetl\Facades;

use Illuminate\Support\Facades\Facade;
use Windsor\Phetl\TransformationPipeline;

/**
 * @see \Windsor\Phetl\TransformationPipeline
 */
class Transform extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return TransformationPipeline::class;
    }
}
