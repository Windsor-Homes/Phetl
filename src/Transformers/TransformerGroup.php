<?php

namespace Windsor\Phetl\Transformers;

use Illuminate\Support\Enumerable;
use Windsor\Phetl\Contracts\Transformer;

class TransformerGroup implements Transformer
{
    /**
     * @var Transformer[]
     */
    protected $transformers = [];

    public function __construct(array $transformers = [])
    {
        $this->transformers = $transformers;
    }

    public function addTransformer(Transformer $transformer)
    {
        $this->transformers[] = $transformer;
    }

    public function transform(Enumerable $dataset): Enumerable
    {
        foreach ($this->transformers as $transformer) {
            $dataset = $transformer->transform($dataset);
        }
        return $dataset;
    }
}