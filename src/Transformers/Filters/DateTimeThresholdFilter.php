<?php

namespace Windsor\Phetl\Transformers\Filters;

use Illuminate\Support\Enumerable;

class DateTimeThresholdFilter extends BaseFilter
{
    protected string $column;
    protected string $operator;
    protected string $threshold;

    public function __construct(
        string $column,
        string $operator,
        string $threshold,
    ) {
        if (!in_array($operator, ['=', '==', '===', '>=', '>', '<=', '<'])) {
            throw new \InvalidArgumentException("Invalid operator: {$operator}");
        }

        $this->column = $column;
        $this->operator = $operator;
        $this->threshold = $threshold;
    }

    /**
     * Make a new DateTimeThresholdFilter instance.
     *
     * @param string $column
     * @param string $operator
     * @param string $threshold
     * @return self
     */
    public static function make(
        string $column,
        string $operator,
        string $threshold,
    ): self {
        return new self($column, $operator, $threshold);
    }

    /**
     * Apply the filter to the dataset.
     *
     * @param Enumerable $dataset
     * @return Enumerable
     */
    public function filter(Enumerable $dataset): Enumerable
    {
        return $dataset->filter(function ($row) {
            if (!key_exists($this->column, $row)) {
                throw new \InvalidArgumentException("Column '{$this->column}' not found in dataset.");
            }

            $value = $row[$this->column] ?? null;
            if (!$value) {
                return false;
            }

            $threshold = strtotime($this->threshold);
            $value = strtotime($value);

            return match ($this->operator) {
                '=', '==', '===' => $value == $threshold,
                '>=' => $value >= $threshold,
                '>' => $value > $threshold,
                '<=' => $value <= $threshold,
                '<' => $value < $threshold,
                default => false,
            };
        });
    }
}