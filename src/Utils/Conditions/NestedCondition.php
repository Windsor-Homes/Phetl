<?php

namespace Windsor\Phetl\Utils\Conditions;

/**
 * Condition that groups multiple conditions together
 */
class NestedCondition extends Condition
{
    public function __construct(
        protected array $conditions,
        protected string $conjunction = 'and',
        protected bool $negate = false,
    ) {}

    public static function make(
        array $conditions,
        string $conjunction = 'and',
        bool $negate = false
    ): self {
        return new self($conditions, $conjunction, $negate);
    }

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

    public function toArray(): array
    {
        $conditions = array_map(
            fn ($condition) => $condition->toArray(),
            $this->conditions
        );

        return [
            'conditions' => $conditions,
            'conjunction' => $this->conjunction,
            'negate' => $this->negate,
        ];
    }
}