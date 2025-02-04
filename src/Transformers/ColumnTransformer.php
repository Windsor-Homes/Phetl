<?php

namespace Windsor\Phetl\Transformers;

use Windsor\Phetl\Contracts\Transformer;
use Illuminate\Support\Enumerable;

abstract class ColumnTransformer implements Transformer
{
    private array|string $columns;

    public function __construct(array|string $columns)
    {
        if (is_string($columns)) {
            $columns = explode(',', $columns);
        }

        $columns = array_map('trim', $columns);

        $this->columns = $columns;
    }

    public function transform(Enumerable $dataset): Enumerable
    {
        return $dataset->map(function ($row, $i) {
            foreach ($this->columns as $column) {
                if (!isset($row[$column])) {
                    continue;
                }

                $row[$column] = $this->transformColumnValue($row[$column]);
            }
            return $row;
        });
    }

    abstract protected function transformColumnValue($column);
}