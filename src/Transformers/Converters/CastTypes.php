<?php

namespace Windsor\Phetl\Transformers;

class CastTypes extends ColumnTransformer
{
    private array $types;

    public function __construct(array $types)
    {
        $this->types = $types;
    }

    protected function transformColumnValue($column)
    {
        $type = $this->types[$column] ?? 'string';
        return match ($type) {
            'int' => (int) $column,
            'float' => (float) $column,
            'bool' => (bool) $column,
            default => $column,
        };
    }
}