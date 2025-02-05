<?php

namespace Windsor\Phetl\Transformers\Filters;

use Illuminate\Support\Enumerable;

class OffsetFilter extends BaseFilter
{
    protected int $offset;
    protected ?int $length;

    public function __construct(int $offset, ?int $length = null)
    {
        $this->offset = $offset;
        $this->length = $length;
    }

    /**
     * Make a new OffsetFilter instance.
     *
     * @param int $offset
     * @return self
     */
    public static function make(int $offset, ?int $length = null): self
    {
        return new self($offset, $length);
    }

    /**
     * Apply the filter to the dataset.
     *
     * @param Enumerable $dataset
     * @return Enumerable
     */
    public function filter(Enumerable $dataset): Enumerable
    {
        return $dataset->slice($this->offset, $this->length);
    }
}