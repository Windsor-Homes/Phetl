<?php

namespace Windsor\Phetl;

class TransformationPipeline
{
    /**
     * @var array
     */
    protected $transformations = [];

    /**
     * @param string $transformation
     * @return $this
     */
    public function add(string $transformation)
    {
        $this->transformations[] = $transformation;
        return $this;
    }

    /**
     * @return array
     */
    public function getTransformations()
    {
        return $this->transformations;
    }
}