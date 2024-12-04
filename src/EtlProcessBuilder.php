<?php

namespace Windsor\Phetl;

use Windsor\Phetl\Contracts\Extractor;

class EtlProcessBuilder
{
    protected $process;

    public function __construct()
    {
       $this->process = new Pipeline();
    }

    public function extract($extractor, $config = [])
    {
        if (is_string($extractor)) {
            $extractor = app($extractor);
        }

        if (! $extractor instanceof Extractor) {
            throw new \Exception('Extractor must implement Extractor interface');
        }

        $this->process->addExtractor($extractor, $config);

        return $this;
    }
}