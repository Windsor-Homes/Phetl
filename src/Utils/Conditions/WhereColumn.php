<?php

namespace Windsor\Phetl\Utils\Conditions;

class WhereColumn extends Where implements Condition
{
    use MakesComparisons;

    protected string $field;

    protected string $operator;

    protected array|string $column_value;

    protected string $conjunction = 'and';

    protected bool $negate = false;

    public function __construct(
        string $field,
        string $operator,
        array|string $column_value,
        string $conjunction = 'and',
        bool $negate = false
    ) {
        $this->field = $field;
        $this->operator = $operator;
        $this->column_value = $column_value;
        $this->conjunction = $conjunction;
        $this->negate = $negate;
    }

    public function check($row): bool
    {
        $row_value = $row[$this->field] ?? null;

        // if the operator is 'in' or 'between', the column value should be an array, so we map the row values
        if (in_array($this->operator, ['in', 'between'])) {
            $target = array_map(
                fn ($column) => $row[$column] ?? null,
                $this->column_value
            );
        }
        else {
            $target = $row[$this->column_value] ?? null;
        }

        return $this->compare($row_value, $target, $this->negate);
    }
}