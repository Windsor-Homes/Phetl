<?php

namespace Windsor\Phetl\Transformers\Converters;

use Illuminate\Support\Enumerable;

abstract class BaseConverter
{
    abstract protected function convert(Enumerable $dataset): Enumerable;

    public function transform(Enumerable $dataset): Enumerable
    {
        return $this->convert($dataset);
    }
}