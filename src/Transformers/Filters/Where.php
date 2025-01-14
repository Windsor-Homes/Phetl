<?php

namespace Windsor\Phetl\Transformers\Filters;

use Illuminate\Support\Enumerable;
use Windsor\Phetl\Transformers\Transformer;

class Where extends Transformer
{
    /**
     * @param callable|string $key
     * @param mixed $operator
     * @param mixed $value
     */
    public function __construct(
        private $key,
        private mixed $operator = null,
        private mixed $value = null,
    ) {}

    public function transform(Enumerable $dataset): Enumerable
    {
        return $dataset->where(
            $this->key,
            $this->operator,
            $this->value
        );
    }
}