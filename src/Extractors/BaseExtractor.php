<?php

namespace Windsor\Phetl\Extractors;

use Windsor\Phetl\Contracts\Extractor as Extractor;
use Illuminate\Support\Collection;
use Illuminate\Support\Enumerable;
use Windsor\Phetl\Concerns\HasLifecycleHooks;

abstract class BaseExtractor implements Extractor
{
    use HasLifecycleHooks;

    abstract public function extract(): Enumerable;

    public function run(): Enumerable
    {
        $this->runHooks('before-extraction', $this);

        $data = $this->extract();

        $this->runHooks('after-extraction', $this, $data);

        return $data;
    }

    public function __invoke(): Enumerable
    {
        return $this->run();
    }

    /**
     * Set a callable that will be executed after the data is extracted from the response.
     *
     * The callable will have access to the Extractor instance and the Extracted data.
     *
     * @param callable(Extractor, Collection) $callback
     * @return static
     */
    public function afterExtraction(callable $callback): static
    {
        $this->addHook('after-extraction', $callback);

        return $this;
    }

    /**
     * Set a callable that will be executed before the data is extracted.
     *
     * The callable will have access to the Extractor instance
     *
     * @param callable(Extractor) $callback
     * @return static
     */
    public function beforeExtraction(callable $callback): static
    {
        $this->addHook('before-extraction', $callback);

        return $this;
    }
}