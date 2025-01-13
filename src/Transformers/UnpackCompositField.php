<?php

namespace Windsor\Phetl\Transformers;

class UnpackCompositField extends RowTransformer
{
    private string $composit_field;

    private array $fields;

    /**
     * @var string|callable $separator
     */
    private mixed $separator;

    private bool $include_original_field;

    public function __construct(
        string $composit_field,
        array $fields,
        string|callable $separator = ',',
        bool $include_original_field = false
    ) {
        $this->composit_field = $composit_field;
        $this->fields = $fields;
        $this->separator = $separator;
        $this->include_original_field = $include_original_field;
    }

    protected function transformRow($row)
    {
        $values = [];

        if (is_callable($this->separator)) {
            $values = call_user_func($this->separator, $row);
        }
        elseif (is_string($this->separator)) {
            $values = explode($this->separator, $row[$this->composit_field]);
        }

        foreach ($this->fields as $i => $field) {
            $value = $values[$i] ?? null;
            $row[$field] = $value;
        }

        if ($this->include_original_field === false) {
            unset($row[$this->composit_field]);
        }

        return $row;
    }
}