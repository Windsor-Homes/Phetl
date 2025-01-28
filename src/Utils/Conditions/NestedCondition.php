<?php

namespace Windsor\Phetl\Utils\Conditions;

/**
 * Condition that groups multiple conditions together
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
class NestedCondition extends Condition
{
    public function __construct(
        protected array $conditions,
        protected string $conjunction = 'and',
        protected bool $negate = false,
    ) {}

    public function check($row): bool
    {
        if (empty($this->conditions)) {
            return false;
        }

        $result = null;

        foreach ($this->conditions as $condition) {
            $condition_result = $condition->check($row);

            if ($result === null) {
                $result = $condition_result;
                continue;
            }

            if ($condition->conjunction === 'and') {
                $result = $result && $condition_result;
            }
            elseif ($condition->conjunction === 'or') {
                $result = $result || $condition_result;
            }
        }

        return $result;
    }
}