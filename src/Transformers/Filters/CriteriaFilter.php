<?php

namespace Windsor\Phetl\Transformers\Filters;

use Illuminate\Support\Enumerable;
use Windsor\Phetl\Utils\Conditions\Builder;

class CriteriaFilter extends BaseFilter
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
    public static function make(
        array|Builder|\Closure|null $criteria = null
    ): self {
        if ($criteria instanceof Builder) {
            return new self($criteria);
        }

        $builder = Builder::make();

        if (is_array($criteria)) {
            $builder = $builder->addArrayOfConditions($criteria);
        }
        elseif ($criteria instanceof \Closure) {
            $builder = $criteria($builder);
        }

        return new self($builder);
    }

    /**
     * Apply the filter to the dataset.
     *
     * @param Enumerable $dataset
     * @return Enumerable
     */
    public function filter(Enumerable $dataset): Enumerable
    {
        return $dataset->filter([$this->criteria, 'evaluate']);
    }
}