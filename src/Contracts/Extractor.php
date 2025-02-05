<?php

namespace Windsor\Phetl\Contracts;

use Illuminate\Support\Enumerable;

interface Extractor
{
    /**
     * Run the extraction process.
     *
     * @return Enumerable
     */
    public function run(): Enumerable;
}