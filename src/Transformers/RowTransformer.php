<?php

namespace Windsor\Phetl\Transformers;

use Illuminate\Support\Enumerable;
use Windsor\Phetl\Contracts\Transformer;

abstract class RowTransformer implements Transformer
{
    public function transform(Enumerable $dataset): Enumerable
    {
        return $dataset->map([$this, 'transformRow']);
    }

    abstract protected function transformRow($row);
}