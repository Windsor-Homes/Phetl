<?php

namespace Windsor\Phetl\Utils\Conditions;


trait MakesComparisons
{
    protected function compare($value, $target, $negate = false): bool
    {
        $result = match ($this->operator) {
            '==' => $value == $target,
            '!=' => $value != $target,
            '===' => $value === $target,
            '!==' => $value !== $target,
            '>' => $value > $target,
            '>=' => $value >= $target,
            '<' => $value < $target,
            '<=' => $value <= $target,
            'in' => $this->compareIn($value, $target),
            'between' => $this->compareBetween($value, $target),
            default => false,
        };

        if ($negate) {
            return !$result;
        }

        return $result;
    }

    protected function compareIn($value, $target): bool
    {
        return in_array($value, $target);
    }

    protected function compareBetween($value, $target): bool
    {
        return $value >= $target[0] && $value <= $target[1];
    }
}