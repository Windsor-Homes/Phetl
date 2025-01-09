<?php

namespace Windsor\Phetl\Transformers;

use Illuminate\Support\Enumerable;

abstract class Transformer
{
    abstract public function __invoke(Enumerable $data);

    public function transformDataset(Enumerable $data): Enumerable
    {
        return $data->map([$this, 'transform']);
    }

    /**
     * Transform the data
     *
     * @param mixed $data
     * @return mixed
     */
    abstract public function transform($data);
}