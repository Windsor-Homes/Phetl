<?php

namespace Windsor\Phetl\Transformers;

use Illuminate\Support\Str;
use Windsor\Phetl\Enums\StringCaseType;

class ConvertHeaderCasing extends RowTransformer
{
    public function __construct(
        protected string $casing = 'lower'
    ) {}

    protected function transformRow($row)
    {
        if ($this->casing === StringCaseType::LOWER) {
            return array_change_key_case($row, CASE_LOWER);
        }
        elseif ($this->casing === StringCaseType::UPPER) {
            return array_change_key_case($row, CASE_UPPER);
        }

        $new_row = [];

        foreach ($row as $key => $value) {
            $new_key = match ($this->casing) {
                StringCaseType::TITLE => Str::title($key),
                StringCaseType::SNAKE => Str::snake($key),
                StringCaseType::CAMEL => Str::camel($key),
                StringCaseType::PASCAL => Str::study($key),
                StringCaseType::KEBAB => Str::kebab($key),
                default => $key,
            };

            $new_row[$new_key] = $value;
        }

        return $new_row;
    }
}