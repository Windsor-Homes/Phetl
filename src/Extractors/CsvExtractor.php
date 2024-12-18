<?php

namespace Windsor\Phetl\Extractors;

use Illuminate\Support\Enumerable;
use Windsor\Phetl\Contracts\Extractor;

class CsvExtractor implements Extractor
{
    public function extract(): Enumerable
    {
        return collect();
    }
}
