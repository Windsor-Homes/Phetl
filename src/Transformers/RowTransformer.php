<?php

namespace Windsor\Phetl\Transformers;

use Illuminate\Support\Enumerable;

class RowTransformer extends Transformer
{
    public function transform(Enumerable $dataset): Enumerable
    {
        return $dataset->map([$this, 'transformRow']);
    }

    abstract protected function transformRow($row);
}