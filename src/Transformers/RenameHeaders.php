<?php

namespace Windsor\Phetl\Transformers;

class RenameHeaders extends RowTransformer
{
    private array $headers;

    /**
     * RenameHeaders constructor. Should be an associative array where the key is the old header name, and the value is the new header name.
     *
     * @param array<string, string> $headers
     */
    public function __construct(array $headers)
    {
        $this->headers = $headers;
    }

    protected function transformRow($row)
    {
        $newRow = [];
        foreach ($this->headers as $old => $new) {
            if (isset($row[$old])) {
                $newRow[$new] = $row[$old];
            }
        }
        return $newRow;
    }
}