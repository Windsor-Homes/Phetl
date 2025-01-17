<?php

namespace Windsor\Phetl\Transformers\Filters;

use Windsor\Phetl\Transformers\Transformer;


class CriteriaFilter
{
    private $conditions = [];

    /**
     * All of the available clause operators.
     *
     * @var string[]
     */
    public $operators = [
        '=', '<', '>', '<=', '>=', '<>', '!=', '<=>', 'in', 'not in',
    ];


    public function getConditions(): array
    {
        return $this->conditions;
    }

    public function where(
        array|callable|string $field,
        mixed $operator = null,
        mixed $value = null,
        string $boolean = 'and',
        bool $negate = false
    ) {
        /**
         * If the field is an array, we will assume it is either an array of
         * key-value pairs, or an array of condition arrays.
         */
        if (is_array($field)) {
            return $this->addArrayOfConditions($field, $boolean, 'where');
        }

        // Here we will make some assumptions about the operator. If only 2 values are
        // passed to the method, we will assume that the operator is an equals sign
        // and keep going. Otherwise, we'll require the operator to be passed in.
        [$value, $operator] = $this->prepareValueAndOperator(
            $value, $operator, func_num_args() === 2
        );

        // If the field is actually a Closure instance, we will assume the developer
        // wants to begin a nested condition which is wrapped in parentheses.
        // We will add that Closure to the filter and return back out immediately.
        if ($field instanceof \Closure && is_null($operator)) {
            return $this->whereNested($field, $boolean);
        }

        // If the given operator is not found in the list of valid operators we will
        // assume that the developer is just short-cutting the '=' operators and
        // we will set the operators to '=' and set the values appropriately.
        if ($this->invalidOperator($operator)) {
            [$value, $operator] = [$operator, '='];
        }

        // If the value is "null", we will just assume the developer wants to add a where null condition to the filter. So, we will allow a short-cut here to that method for convenience so the developer doesn't have to check.
        if (is_null($value)) {
            return $this->whereNull($field, $boolean, $operator !== '=');
        }

        $type = 'Basic';

        // Now that we are working with just a simple filter we can put the elements
        // in our array and add the filter binding to our array of bindings that
        // will be bound to each SQL statements when it is finally executed.
        $this->conditions[] = compact(
            'type', 'field', 'operator', 'value', 'boolean', 'negate'
        );
    }

    /**
     * Prepare the value and operator for a condition.
     *
     * @param  string  $value
     * @param  string  $operator
     * @param  bool  $use_default
     * @return array
     *
     * @throws \InvalidArgumentException
     */
    public function prepareValueAndOperator($value, $operator, $use_default = false)
    {
        if ($use_default) {
            return [$operator, '='];
        }
        elseif ($this->invalidOperatorAndValue($operator, $value)) {
            throw new \InvalidArgumentException('Illegal operator and value combination.');
        }

        return [$value, $operator];
    }

    /**
     * Determine if the given operator and value combination is legal.
     *
     * Prevents using Null values with invalid operators.
     *
     * @param  string  $operator
     * @param  mixed  $value
     * @return bool
     */
    protected function invalidOperatorAndValue($operator, $value)
    {
        return is_null($value) && in_array($operator, $this->operators) &&
             ! in_array($operator, ['=', '<>', '!=']);
    }

    /**
     * Determine if the given operator is supported.
     *
     * @param  string  $operator
     * @return bool
     */
    protected function invalidOperator($operator)
    {
        return ! is_string($operator)
            || (! in_array(strtolower($operator), $this->operators, true));
    }

    public function addArrayOfConditions($conditions, $boolean, $method = 'where')
    {
        return $this->whereNested(
            function ($filter) use ($conditions, $boolean, $method) {
                foreach ($conditions as $key => $value) {
                    if (is_numeric($key) && is_array($value)) {
                        $filter->{$method}(
                            ...array_values($value),
                            boolean: $boolean
                        );
                    }
                    else {
                        $filter->{$method}($key, '=', $value, $boolean);
                    }
                }
            },
            $boolean
        );
    }

    public function whereNested($callback, $boolean = 'and', $negate = false)
    {
        $filter = new CriteriaFilter();
        $callback($filter);

        $this->conditions[] = [
            'type' => 'nested',
            'conditions' => $filter->getConditions(),
            'boolean' => $boolean,
            'negate' => $negate,
        ];

        return $this;
    }

    public function orWhere($field, $operator, $value)
    {
        $this->where($field, $operator, $value, 'or');

        return $this;
    }

    public function whereNot($field, $operator, $value)
    {
        $this->where($field, $operator, $value, 'and', true);

        return $this;
    }

    public function orWhereNot($field, $operator, $value)
    {
        $this->where($field, $operator, $value, 'or', true);

        return $this;
    }

    public function whereColumn(
        string $first,
        string $operator,
        ?string $second = null,
        string $boolean = 'and',
        bool $negate = false
    ): static {
        if (is_null($second)) {
            $second = $operator;
            $operator = '=';
        }

        $this->conditions[] = [
            'type' => 'whereColumn',
            'field' => $first,
            'operator' => $operator,
            'other_field' => $second,
            'boolean' => $boolean,
            'negate' => $negate,
        ];

        return $this;
    }

    public function orWhereColumn($first, $operator, $second)
    {
        $this->whereColumn($first, $operator, $second, 'or');

        return $this;
    }

    public function whereColumnNot($first, $operator, $second)
    {
        $this->whereColumn($first, $operator, $second, 'and', true);

        return $this;
    }

    public function orWhereColumnNot($first, $operator, $second)
    {
        $this->whereColumn($first, $operator, $second, 'or', true);

        return $this;
    }

    public function whereIn($field, array $values, $boolean = 'and', $negate = false)
    {
        $this->conditions[] = [
            'type' => 'basic',
            'field' => $field,
            'operator' => 'in',
            'value' => $values,
            'boolean' => $boolean,
            'negate' => $negate,
        ];

        return $this;
    }

    public function whereNotIn($field, array $values, $boolean = 'and')
    {
        return $this->whereIn($field, $values, $boolean, true);
    }

    public function orWhereIn($field, array $values)
    {
        return $this->whereIn($field, $values, 'or');
    }

    public function orWhereNotIn($field, array $values)
    {
        return $this->whereIn($field, $values, 'or', true);
    }

    public function whereBetween($field, array $values, $boolean = 'and', $negate = false)
    {
        $this->where(function ($filter) use ($field, $values) {
            $filter->where($field, '>=', $values[0], 'and');
            $filter->where($field, '<=', $values[1], 'and');
        }, $boolean, $negate);

        return $this;
    }

    public function whereNotBetween($field, array $values, $boolean = 'and')
    {
        return $this->whereBetween($field, $values, $boolean, true);
    }

    public function orWhereBetween($field, array $values)
    {
        return $this->whereBetween($field, $values, 'or');
    }

    public function orWhereNotBetween($field, array $values)
    {
        return $this->whereBetween($field, $values, 'or', true);
    }

    public function whereNull($field, $boolean = 'and', $negate = false)
    {
        $this->conditions[] = [
            'type' => 'basic',
            'field' => $field,
            'boolean' => $boolean,
            'negate' => $negate,
        ];

        return $this;
    }

    /**
     * Evaluates the given data against the specified conditions.
     *
     * @param mixed $data The data to be evaluated.
     * @param mixed $conditions The conditions to evaluate the data against.
     * @return mixed The result of the evaluation.
     */
    public function evaluate($data, $conditions)
    {
        if (empty($conditions)) {
            return true;
        }

        $expr_result = null;
        foreach ($conditions as $condition) {
            $type =& $condition['type'];
            $boolean =& $condition['boolean'];

            $result = match ($type) {
                'basic' => $this->evaluateCondition($data, $condition),
                'whereColumn' => $this->evaluateWhereColumn($data, $condition),
                'nested' => $this->evaluate($data, $condition['conditions']),
            };

            if ($expr_result === null) {
                $expr_result = $result;
                continue;
            }

            if ($boolean == 'and') {
                $expr_result = $expr_result && $result;
            }
            elseif ($boolean == 'or') {
                $expr_result = $expr_result || $result;
            }
        }

        return $expr_result;
    }

    public function evaluateCondition($row, $condition)
    {
        [
            'field' => $field,
            'operator' => $operator,
            'value' => $value,
            'negate' => $negate,
        ] = $condition;

        $field_value = $row[$field];

        $result = match ($operator) {
            '==' => $field_value == $value,
            '!=' => $field_value != $value,
            '===' => $field_value === $value,
            '!==' => $field_value !== $value,
            '>' => $field_value > $value,
            '>=' => $field_value >= $value,
            '<' => $field_value < $value,
            '<=' => $field_value <= $value,
            'in' => in_array($field_value, $value),
            'not in' => !in_array($field_value, $value),
            'contains' => strpos($field_value, $value) !== false,
            'doesnt contain' => strpos($field_value, $value) === false,
            'between' => $field_value >= $value[0] && $field_value <= $value[1],
            'not between' => !($field_value >= $value[0] && $field_value <= $value[1]),
            default => false,
        };

        if ($negate) {
            $result = !$result;
        }

        return $result;
    }

    public function evaluateWhereColumn($row, $condition)
    {
        [$field, $operator, $other_field, $boolean] = $condition;

        $other_field_value = $row[$other_field];

        return $this->evaluateCondition($row, [$field, $operator, $other_field_value, $boolean]);
    }

    public function sandbox()
    {
        $foo = 'bar';

        ($foo == 'bar'); // equals:
        ['basic', 'foo', '==', 'bar', null];

        ($foo == 'bar' && $foo == 'baz'); // equals:
        [
            ['basic', 'foo', '==', 'bar', null],
            ['basic', 'foo', '==', 'baz', 'and'],
        ];

        ($foo == 'bar' || $foo == 'baz'); // equals:
        [
            ['basic', 'foo', '==', 'bar', null],
            ['basic', 'foo', '==', 'baz', 'or'],
        ];

        ($foo == 'bar' && ($foo == 'baz' || $foo == 'qux')); // equals:
        [
            ['basic', 'foo', '==', 'bar', null],
            ['nested',
                ['basic', 'foo', '==', 'baz', null],
                ['basic', 'foo', '==', 'qux', 'or'],
            ],
        ];

        ($foo == 'bar' && $foo == 'baz' || $foo == 'qux'); // equals:
        [
            ['basic', 'foo', '==', 'bar', null],
            ['basic', 'foo', '==', 'baz', 'and'],
            ['basic', 'foo', '==', 'qux', 'or'],
        ];

    }
}