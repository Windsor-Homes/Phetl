<?php

namespace Windsor\Phetl\Transformers\Filters;

use Illuminate\Support\Collection;
use Illuminate\Support\Enumerable;
use Windsor\Phetl\Utils\Conditions\Builder;
use Windsor\Phetl\Contracts\Filter;

class CriteriaFilter implements Filter
{
    protected Builder $criteria;

    public function __construct(Builder $criteria)
    {
        $this->criteria = $criteria;
    }

    /**
     * Make a new CriteriaFilter instance.
     *
     * You may pass an array of criteria or a closure that receives a Builder instance.
     * Passing an array will be equivalent to calling `addArrayOfConditions` on the builder.
     *
     * @param array|\Closure|null|null $criteria
     * @return self
     */
    public static function make(array|\Closure|null $criteria = null): self
    {
        $builder = Builder::make();

        if (is_array($criteria)) {
            $builder = $builder->addArrayOfConditions($criteria);
        }
        elseif ($criteria instanceof \Closure) {
            $builder = $criteria($builder);
        }

        return new self($builder);
    }

    public function transform(Enumerable $dataset): Enumerable
    {
        return $dataset->filter([$this->criteria, 'evaluate']);
    }
}