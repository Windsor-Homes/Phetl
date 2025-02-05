<?php

namespace Windsor\Phetl\Extractors;

use Illuminate\Support\Enumerable;
use Spatie\SimpleExcel\SimpleExcelReader;

class CsvExtractor extends BaseExtractor
{
    protected SimpleExcelReader $reader;


    public function __construct(?string $path = null)
    {
        if ($path === null) {
            return;
        }

        $this->reader = SimpleExcelReader::create($path);
    }

    /**
     * Set the path to the CSV file.
     *
     * @param string $path
     * @return static
     */
    public function path(string $path): static
    {
        if (isset($this->reader)) {
            throw new \BadMethodCallException(
                'Cannot set the path after the reader has been created.'
            );
        }

        $this->reader = SimpleExcelReader::create($path);
        return $this;
    }

    /**
     * Forward method calls to the SimpleExcelReader instance.
     *
     * @param mixed $method
     * @param mixed $args
     * @return static
     */
    public function __call($method, $args)
    {
        if (!isset($this->reader)) {
            throw new \BadMethodCallException(
                'Cannot call methods on the reader before it has been created.'
            );
        }

        if (!method_exists($this->reader, $method)) {
            throw new \BadMethodCallException(sprintf(
                'Method %s does not exist on %s',
                $method,
                SimpleExcelReader::class
            ));
        }

        $this->reader->{$method}(...$args);

        return $this;
    }

    /**
     * Extract the data from the CSV file.
     *
     * @return Enumerable
     */
    public function extract(): Enumerable
    {
        return $this->reader->getRows();
    }
}
