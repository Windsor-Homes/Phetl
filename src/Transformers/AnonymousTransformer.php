<?php

namespace Windsor\Phetl\Transformers;

use Illuminate\Support\Enumerable;

class AnonymousTransformer extends Transformer
{
    /**
     * @var callable
     */
    protected $transformer;

    public function __construct(callable $transformer)
    {
        $this->transformer = $transformer;
    }

    public function transform(Enumerable $data)
    {
        return $data->map($this->transformer);
    }
}