<?php

namespace Windsor\Phetl\Transformers\Filters;

use Illuminate\Support\Arr;
use Illuminate\Support\Enumerable;

class DuplicatesFilter extends BaseFilter
{
    protected mixed $key;

    protected bool $strict = false;

    /**
     * Create a new DuplicatesFilter instance.
     *
     * @param array|(callable(TValue, TKey): mixed)|null|string $key
     * @param boolean $strict
     */
    public function __construct(
        array|callable|null|string $key = null,
        bool $strict = false
    ) {
        $this->key = $key;
        $this->strict = $strict;
    }

    /**
     * Make a new DuplicatesFilter instance.
     *
     * @param array|(callable(TValue, TKey): mixed)|null|string $key
     * @return self
     */
    public static function make(
        array|callable|null|string $key = null,
        bool $strict = false
    ): self {
        return new self($key, $strict);
    }

    /**
     * Apply the filter to the dataset.
     *
     * @param Enumerable $dataset
     * @return Enumerable
     */
    public function filter(Enumerable $dataset): Enumerable
    {
        if (is_array($this->key)) {
            $columns = $this->key;
            $this->key = function ($item, $key) use ($columns) {
                return Arr::only($item, $columns);
            };
        }

        return $dataset->unique($this->key, $this->strict);
    }
}