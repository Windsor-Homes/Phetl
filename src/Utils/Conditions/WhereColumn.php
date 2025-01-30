<?php

namespace Windsor\Phetl\Utils\Conditions;

class WhereColumn extends Condition
{
    use MakesComparisons;

    public function __construct(
        protected string $field,
        protected string $operator,
        protected array|string $column_value,
        protected string $conjunction = 'and',
        protected bool $negate = false
    ) {}

    public static function make(
        string $field,
        string $operator,
        array|string $column_value,
        string $conjunction = 'and',
        bool $negate = false
    ): self {
        return new self($field, $operator, $column_value, $conjunction, $negate);
    }

    public function check($row): bool
    {
        $row_value = $row[$this->field] ?? null;
        $target = $this->resolveColumnValue($row);

        return $this->compare($row_value, $target);
    }

    public function resolveColumnValue($row)
    {
        // if the column value is an array, we map the row values
        if (is_array($this->column_value)) {
            return array_map(
                fn ($column) => $row[$column] ?? null,
                $this->column_value
            );
        }

        return $row[$this->column_value] ?? null;
    }
}