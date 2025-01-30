<?php

namespace Windsor\Phetl\Utils\Conditions;


class Where extends Condition
{
    use MakesComparisons;

    public function __construct(
        protected string $field,
        protected string $operator,
        protected mixed $value,
        protected string $conjunction = 'and',
        protected bool $negate = false,
    ) {}

    public static function make(
        string $field,
        string $operator,
        mixed $value,
        string $conjunction = 'and',
        bool $negate = false
    ): self {
        return new self($field, $operator, $value, $conjunction, $negate);
    }

    public function check($row): bool
    {
        $row_value = $row[$this->field] ?? null;

        return $this->compare($row_value, $this->value);
    }
}