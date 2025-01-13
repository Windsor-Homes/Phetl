<?php

namespace Windsor\Phetl\Transformers;

class AddField extends RowTransformer
{
    private string $field;
    private mixed $definition;

    public function __construct(string $field, mixed $definition)
    {
        $this->field = $field;
        $this->definition = $definition;
    }

    protected function transformRow($row)
    {
        $row[$this->field] = $this->resolveValue($row, $this->definition);
        return $row;
    }

    protected function resolveValue($row, $definition)
    {
        if (is_callable($definition)) {
            return call_user_func($definition, $row);
        }

        return $definition;
    }
}