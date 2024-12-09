<?php

namespace Windsor\Phetl\Contracts;

use Illuminate\Support\Enumerable;

interface Extractor
{
    public function extract(): Enumerable;
}
