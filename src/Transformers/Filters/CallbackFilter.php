<?php

namespace Windsor\Phetl\Transformers\Filters;

use Illuminate\Support\Enumerable;

class CallbackFilter extends BaseFilter
{
    protected $callback;

    protected $method = 'filter';

    /**
     * Create a new CallbackFilter instance.
     *
     * @param (callable(TValue, TKey): bool) $callback
     * @param string $method
     */
    public function __construct(callable $callback, string $method = 'filter')
    {
        $this->callback = $callback;
        $this->method = $method;
    }

    /**
     * Make a new CallbackFilter instance.
     *
     * @param (callable(TValue, TKey): bool) $callback
     * @param string $method
     * @return self
     */
    public static function make(
        callable $callback,
        string $method = 'filter'
    ): self {
        return new self($callback, $method);
    }

    /**
     * Apply the filter to the dataset.
     *
     * @param Enumerable $dataset
     * @return Enumerable
     */
    protected function filter(Enumerable $dataset): Enumerable
    {
        return match ($this->method) {
            'filter' => $dataset->filter($this->callback),
            'reject' => $dataset->reject($this->callback),
            default => throw new \InvalidArgumentException("Invalid method: {$this->method}"),
        };

        return $dataset->filter($this->callback);
    }
}