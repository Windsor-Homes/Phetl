<?php

namespace Windsor\Phetl;

use Illuminate\Http\Client\PendingRequest;
use Windsor\Phetl\Extractors\ApiExtractor;
use Windsor\Phetl\Extractors\CsvExtractor;
use Windsor\Phetl\Extractors\QueryExtractor;

class ExtractorBuilder
{
    public function fromApi(): ApiExtractor
    {
        $request = new PendingRequest;

        return new ApiExtractor($request);
    }

    public function fromCsv(string $path): CsvExtractor
    {
        return new CsvExtractor($path);
    }

    public function fromQuery(
        $query,
        $bindings = [],
        $connection = null
    ): QueryExtractor {
        return new QueryExtractor($query, $bindings, $connection);
    }
}
