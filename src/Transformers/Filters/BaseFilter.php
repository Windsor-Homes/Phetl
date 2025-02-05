<?php

namespace Windsor\Phetl\Transformers\Filters;

use Illuminate\Support\Enumerable;
use Windsor\Phetl\Concerns\HasLifecycleHooks;
use Windsor\Phetl\Contracts\Transformer;

abstract class BaseFilter implements Transformer
{
    use HasLifecycleHooks;

    abstract protected function filter(Enumerable $dataset): Enumerable;

    public function transform(Enumerable $dataset): Enumerable
    {
        $this->runHooks('before-filter', $this, $dataset);

        $dataset = $this->filter($dataset);

        $this->runHooks('after-filter', $this, $dataset);

        return $dataset;
    }

    public function beforeFilter(callable $callback): self
    {
        $this->addHook('before-filter', $callback);
        return $this;
    }

    public function afterFilter(callable $callback): self
    {
        $this->addHook('after-filter', $callback);
        return $this;
    }
}