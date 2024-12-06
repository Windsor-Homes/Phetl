<?php

namespace Windsor\Phetl;

use Illuminate\Http\Client\PendingRequest;
use Windsor\Phetl\Extractors\ApiExtractor;

class ExtractorBuilder
{
    public function fromApi(): ApiExtractor
    {
        $request = new PendingRequest();
        return new ApiExtractor($request);
    }
}