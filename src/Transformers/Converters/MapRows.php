<?php

namespace Windsor\Phetl\Transformers;

use Illuminate\Support\Enumerable;

class MapRows extends Transformer
{
    /**
     * @var callable $callback
     */
    private $callback;

    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }

    public function transform(Enumerable $dataset): Enumerable
    {
        return $dataset->map($this->callback);
    }
}