<?php

namespace Windsor\Phetl\Transformers\Filters;

use Windsor\Phetl\Transformers\Transformer;


class CriteriaFilter
{
    private $conditions = [];


    public function addCondition($type, $field, $operator, $value, $boolean = 'and')
    {
        $this->conditions[] = [
            'type' => $type,
            'field' => $field,
            'operator' => $operator,
            'value' => $value,
        ];

        return $this;
    }

    public function getConditions()
    {
        return $this->conditions;
    }

    public function evaluate($data)
    {
        $results = [];
        foreach ($this->conditions as $condition) {
            $results[] = $this->evaluateCondition($data, $condition);
        }

    }

    public function evaluateCondition($row, $condition)
    {

    }

    public function where($field, $operator, $value)
    {
        if ($field instanceof \Closure) {
            $this->addNestedWhere($field);
            return $this;
        }

        $num_args = func_num_args();

    }

    public function orWhere($field, $operator, $value)
    {
        $num_args = func_num_args();
        if ($num_args === 1) {
            $this->addNestedWhere($field);
            return $this;
        }

        return $this->where($field, $operator, $value, 'or');
    }

    public function addNestedWhere($callback)
    {
        $filter = new CriteriaFilter();
        $callback($filter);
        $this->conditions[] = $filter;
    }
}