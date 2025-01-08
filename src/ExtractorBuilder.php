<?php

namespace Windsor\Phetl;

use Windsor\Phetl\Extractors\ApiExtractor;
use Windsor\Phetl\Extractors\CsvExtractor;
use Windsor\Phetl\Extractors\QueryExtractor;

class ExtractorBuilder
{
    public function fromApi($endpoint = null): ApiExtractor
    {
        return new ApiExtractor($endpoint);
    }

    public function fromCsv(string $path = null): CsvExtractor
    {
        return new CsvExtractor($path);
    }

    public function fromQuery(
        $query = null,
        $bindings = [],
        $connection = null
    ): QueryExtractor {
        return new QueryExtractor($query, $bindings, $connection);
    }
}
