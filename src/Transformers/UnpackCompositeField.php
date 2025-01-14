<?php

namespace Windsor\Phetl\Transformers;

use Illuminate\Support\Fluent;

class UnpackCompositeField extends RowTransformer
{
    private string $composite_field;

    /**
     * @var string|callable $unpacker
     */
    private mixed $unpacker;

    private array $new_fields;

    private bool $include_original_field;

    public function __construct(
        string $composite_field,
        string|callable $unpacker = ',',
        array $new_fields = [],
        bool $include_original_field = false
    ) {
        $this->composite_field = $composite_field;
        $this->unpacker = $unpacker;
        $this->new_fields = $new_fields;
        $this->include_original_field = $include_original_field;
    }

    /**
     * Transform the row; unpack the composit field into multiple fields
     *
     * If unpacker is a string, it will be used to explode the composit field
     * If unpacker is a callable, it will be called with the row as argument and should return an assoc array where the keys are the
     *
     * @param Fluent $row
     * @return mixed
     */
    protected function transformRow($row)
    {
        $values = $this->getValues($row);

        $row = $row->toArray();
        $row = array_merge($row, $values);
        $row = fluent($row);

        if ($this->include_original_field === false) {
            unset($row[$this->composite_field]);
        }

        return fluent($row);
    }

    protected function getValues($row)
    {
        if (is_string($this->unpacker)) {
            $values = explode(
                $this->unpacker,
                $row[$this->composite_field]
            );

            $values = array_map('trim', $values);

            return array_combine($this->new_fields, $values);
        }

        $values = call_user_func($this->unpacker, $row);

        if (!is_array($values)) {
            throw new \Exception('Unpacker must return an array');
        }

        return $values;
    }
}