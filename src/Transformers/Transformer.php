<?php

namespace Windsor\Phetl\Transformers;

use Illuminate\Support\Enumerable;

abstract class Transformer
{
    abstract public function transform(Enumerable $dataset): Enumerable;
}