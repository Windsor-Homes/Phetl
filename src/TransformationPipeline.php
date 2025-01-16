<?php

namespace Windsor\Phetl;

use Windsor\Phetl\Transformers\Transformer;
use Windsor\Phetl\Concerns\HasLifecycleHooks;

class TransformationPipeline
{
    use HasLifecycleHooks;

    /**
     * @var array
     */
    protected $transformers = [];

    /**
     * @return $this
     */
    public function addTransformer(callable|Transformer $transformer)
    {
        $this->transformers[] = $transformer;

        return $this;
    }

    /**
     * @return array
     */
    public function getTransformers()
    {
        return $this->transformers;
    }

    protected function run($data)
    {
        $this->runHooks('start-transformations', $this, $data);

        foreach ($this->transformers as $transformer) {
            $this->runHooks('before-transformer', $transformer, $data);

            $data = $transformer->transform($data);

            $this->runHooks('after-transformer', $transformer, $data);
        }

        $this->runHooks('end-transformations', $this, $data);

        return $data;
    }
}
