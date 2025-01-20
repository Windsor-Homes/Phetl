<?php

namespace Windsor\Phetl\Transformers\Filters;

use Illuminate\Support\Collection;
use Windsor\Phetl\Transformers\Transformer;
use Windsor\Phetl\Utils\Conditions\Builder;

/**
 * Filter data using criteria.
 *
 * @method where($field, $operator, $value, $conjunction = 'and', $negate = false)
 * @method orWhere($field, $operator, $value)
 * @method whereNot($field, $operator, $value)
 * @method orWhereNot($field, $operator, $value)
 * @method whereIn($field, $values)
 * @method orWhereIn($field, $values)
 * @method whereNotIn($field, $values)
 * @method orWhereNotIn($field, $values)
 * @method whereBetween($field, $values)
 * @method orWhereBetween($field, $values)
 * @method whereNotBetween($field, $values)
 * @method orWhereNotBetween($field, $values)
 * @method whereNull($field)
 * @method orWhereNull($field)
 * @method whereNotNull($field)
 * @method orWhereNotNull($field)
 * @method whereColumn($field, $operator, $column_value, $conjunction = 'and', $negate = false)
 * @method orWhereColumn($field, $operator, $column_value)
 * @method whereColumnNot($field, $operator, $column_value)
 * @method orWhereColumnNot($field, $operator, $column_value)
 * @method whereColumnIn($field, $column_value)
 * @method orWhereColumnIn($field, $column_value)
 * @method whereColumnNotIn($field, $column_value)
 * @method orWhereColumnNotIn($field, $column_value)
 * @method whereColumnBetween($field, $column_value)
 * @method orWhereColumnBetween($field, $column_value)
 * @method whereColumnNotBetween($field, $column_value)
 * @method orWhereColumnNotBetween($field, $column_value)
 */
class CriteriaFilter
{
    protected Builder $criteria;

    public function __construct()
    {
        $this->criteria = new Builder();
    }

    public function __call($name, $arguments)
    {
        if (!method_exists($this->criteria, $name)) {
            throw new \BadMethodCallException("Method {$name} does not exist on Conditions\Builder");
        }

        $this->criteria->{$name}(...$arguments);

        return $this;
    }
}