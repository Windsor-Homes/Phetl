<?php

namespace Windsor\Phetl\Contracts;

use Illuminate\Support\Enumerable;

interface Transformer
{
    public function transform(Enumerable $dataset): Enumerable;
}