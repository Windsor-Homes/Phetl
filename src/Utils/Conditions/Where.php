<?php

namespace Windsor\Phetl\Utils\Conditions;


class Where implements Condition
{
    use MakesComparisons;

    protected string $field;

    protected string $operator;

    protected mixed $value;

    protected string $conjunction = 'and';

    protected bool $negate = false;

    public function __construct($field, $operator, $value, $conjunction = 'and', $negate = false)
    {
        $this->field = $field;
        $this->operator = $operator;
        $this->value = $value;
        $this->conjunction = $conjunction;
        $this->negate = $negate;
    }

    public function check($row): bool
    {
        $row_value = $row[$this->field] ?? null;

        return $this->compare($row_value, $this->value, $this->negate);
    }
}