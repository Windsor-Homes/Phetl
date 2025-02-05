<?php

namespace Windsor\Phetl\Transformers;

use Illuminate\Support\Enumerable;
use Windsor\Phetl\Contracts\Transformer;
use Windsor\Phetl\Contracts\Sequenceable;

class Sequence implements Transformer
{
    // group of transformers that will be applied in sequence to a dataset row by row instead of one after the other

    /**
     * @var Transformer&Sequenceable[]
     */
    protected $transformers = [];

    public function __construct(array $transformers = [])
    {
        $this->transformers = $transformers;
    }

    public function addTransformer(Transformer&Sequenceable $transformer)
    {
        $this->transformers[] = $transformer;
    }

    public function transform(Enumerable $dataset): Enumerable
    {
        foreach ($dataset as &$row) {
            foreach ($this->transformers as $transformer) {
                $row = $transformer->transformRow($row);
            }
        }

        return $dataset;
    }
}