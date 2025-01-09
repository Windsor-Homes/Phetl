<?php

namespace Windsor\Phetl\Extractors;

use Illuminate\Support\Enumerable;
use Illuminate\Support\Str;
use Spatie\SimpleExcel\SimpleExcelReader;

/**
 * Extract data from a CSV file.
 *
 * @method CsvExtractor path(string $path) Set the path to the CSV file.
 * @method CsvExtractor delimiter(string $delimiter) Set the delimiter used in the CSV file.
 * @method CsvExtractor enclosure(string $enclosure) Set the enclosure used in the CSV file.
 * @method CsvExtractor escape(string $escape) Set the escape character used in the CSV file.
 * @method CsvExtractor hasHeader(bool $has_header) Set if the CSV file has a header line.
 * @method CsvExtractor ignoreHeader(bool $ignore_header) Set if the header line will be ignored.
 * @method CsvExtractor header(array $header) Set the header fields.
 * @method CsvExtractor trim(bool $trim) Set if the CSV file will be trimmed.
 * @method CsvExtractor strict(bool $strict) Set if the CSV file must have the same number of fields as the header.
 * @method CsvExtractor skipEmptyLines(bool $skip_empty_lines) Set if empty lines will be skipped.
 * @method CsvExtractor encoding(string $encoding) Set the encoding of the CSV file.
 * @method CsvExtractor autoDetectLineEndings(bool $auto_detect_line_endings) Set if the CSV file will be read in binary mode.
 * @method CsvExtractor lineEnding(string $line_ending) Set the line ending used in the CSV file.
 */
class CsvExtractor extends Extractor
{
    /**
     * The path to the CSV file.
     * @var string
     */
    protected string $path;

    /**
     * The delimiter used in the CSV file.
     * @var string
     */
    protected string $delimiter = ',';

    /**
     * The enclosure used in the CSV file.
     * @var string
     */
    protected string $enclosure = '"';

    /**
     * The escape character used in the CSV file.
     * @var string
     */
    protected string $escape = '\\';

    /**
     * If true, the CSV file has a header line, and the first row will be used as the header.
     * @var bool
     */
    protected bool $has_header = true;

    /**
     * If true, the header line will be ignored.
     * @var bool
     */
    protected bool $ignore_header = false;

    /**
     * The header fields. If empty, the first row of the CSV file will be used as the header.
     * @var array
     */
    protected array $header = [];

    /**
     * If true, the CSV file will be trimmed.
     * @var bool
     */
    protected bool $trim = true;

    /**
     * If true, the CSV file must have the same number of fields as the header.
     * @var bool
     */
    protected bool $strict = false;

    /**
     * If true, empty lines will be skipped.
     * @var bool
     */
    protected bool $skip_empty_lines = true;

    /**
     * The encoding of the CSV file.
     * @var string
     */
    protected string $encoding = 'UTF-8';

    /**
     * If true, the CSV file will be read in binary mode.
     * @var bool
     */
    protected bool $auto_detect_line_endings = false;

    /**
     * The line ending used in the CSV file.
     * @var string
     */
    protected string $line_ending = "\n";


    public function __construct(?string $path = null)
    {
        $this->path = $path;
    }

    /**
     * Set a property value dynamically.
     * This will not work for inherited or trait properties.
     *
     * This implementation was chosen to support setter **methods** for properties, without having to write each one manually.
     *
     * @param mixed $method
     * @param mixed $args
     * @return static
     */
    public function __call($method, $args)
    {
        $property = Str::snake($method);
        $own_properties = $this->getOwnProperties();

        if (! property_exists($this, $property)) {
            throw new \BadMethodCallException(
                "Method {$method} does not exist."
            );
        }
        elseif (
            property_exists($this, $property)
            && !in_array($property, $own_properties)
        ) {
            $class_name = static::class;
            throw new \BadMethodCallException("Property {$property} is not owned by $class_name. Cannot dynamically set inherited or trait properties.");
        }

        $this->{$property} = $args[0];
        return $this;
    }

    /**
     * Get the properties that were defined by the current class.
     *
     * @return array
     */
    protected function getOwnProperties(): array
    {
        $class = new \ReflectionClass($this);
        $properties = $class->getProperties();
        $own_properties = [];

        foreach ($properties as $property) {
            $declaring_class = $property->getDeclaringClass()->getName();

            if ($declaring_classs !== $class->getName()) {
                continue;
            }

            $own_properties[] = $property->getName();
        }

        return $own_properties;
    }

    /**
     * Extract the data from the CSV file.
     *
     * @return Enumerable
     */
    public function extract(): Enumerable
    {
        $reader = SimpleExcelReader::create($this->path);



        return collect();
    }
}
