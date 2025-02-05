<?php

namespace Windsor\Phetl\Transformers\Filters;

use Illuminate\Support\Enumerable;

class ValidatorFilter extends BaseFilter
{
    protected array $rules;

    public function __construct(array $rules)
    {
        $this->rules = $rules;
    }

    /**
     * Make a new ValidatorFilter instance.
     *
     * @param array $rules
     * @return self
     */
    public static function make(array $rules): self
    {
        return new self($rules);
    }

    /**
     * Apply the filter to the dataset.
     *
     * @param Enumerable $dataset
     * @return Enumerable
     */
    public function filter(Enumerable $dataset): Enumerable
    {
        return $dataset->filter(function ($row) {
            $validator = validator($row, $this->rules);
            return ! $validator->fails();
        });
    }
}