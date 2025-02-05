<?php

namespace Windsor\Phetl\Transformers\Filters;

use Illuminate\Support\Enumerable;
use Windsor\Phetl\Contracts\Transformer;

abstract class BaseFilter implements Transformer
{
    abstract protected function filter(Enumerable $dataset): Enumerable;

    public function transform(Enumerable $dataset): Enumerable
    {
        return $this->filter($dataset);
    }
}