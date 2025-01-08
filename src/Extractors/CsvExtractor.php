<?php

namespace Windsor\Phetl\Extractors;

use Illuminate\Support\Enumerable;

class CsvExtractor extends Extractor
{
    /**
     * The path to the CSV file. Relative to the storage path.
     * @var string
     */
    protected string $storage_path;

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


    public function __construct(?string $storage_path = null)
    {
        $this->storage_path = $storage_path;
    }

    /**
     * Set the delimiter used in the CSV file.
     *
     * @param string $delimiter
     * @return $this
     */
    public function delimiter(string $delimiter): static
    {
        $this->delimiter = $delimiter;
        return $this;
    }

    /**
     * Set the enclosure used in the CSV file.
     *
     * @param string $enclosure
     * @return $this
     */
    public function enclosure(string $enclosure): static
    {
        $this->enclosure = $enclosure;
        return $this;
    }

    /**
     * Set the escape character used in the CSV file.
     *
     * @param string $escape
     * @return $this
     */
    public function escape(string $escape): static
    {
        $this->escape = $escape;
        return $this;
    }

    /**
     * Set if the CSV file has a header line.
     *
     * @param bool $has_header
     * @return $this
     */
    public function hasHeader(bool $has_header): static
    {
        $this->has_header = $has_header;
        return $this;
    }

    /**
     * Set if the header line will be ignored.
     *
     * @param bool $ignore_header
     * @return $this
     */
    public function ignoreHeader(bool $ignore_header): static
    {
        $this->ignore_header = $ignore_header;
        return $this;
    }

    /**
     * Set the header fields.
     *
     * @param array $header
     * @return $this
     */
    public function header(array $header): static
    {
        $this->header = $header;
        return $this;
    }

    /**
     * Set if the CSV file will be trimmed.
     *
     * @param bool $trim
     * @return $this
     */
    public function trim(bool $trim): static
    {
        $this->trim = $trim;
        return $this;
    }

    /**
     * Set if the CSV file must have the same number of fields as the header.
     *
     * @param bool $strict
     * @return $this
     */
    public function strict(bool $strict): static
    {
        $this->strict = $strict;
        return $this;
    }

    /**
     * Set if empty lines will be skipped.
     *
     * @param bool $skip_empty_lines
     * @return $this
     */
    public function skipEmptyLines(bool $skip_empty_lines): static
    {
        $this->skip_empty_lines = $skip_empty_lines;
        return $this;
    }

    /**
     * Set the encoding of the CSV file.
     *
     * @param string $encoding
     * @return $this
     */
    public function encoding(string $encoding): static
    {
        $this->encoding = $encoding;
        return $this;
    }

    /**
     * Set if the CSV file will be read in binary mode.
     *
     * @param bool $auto_detect_line_endings
     * @return $this
     */
    public function autoDetectLineEndings(
        bool $auto_detect_line_endings
    ): static {
        $this->auto_detect_line_endings = $auto_detect_line_endings;
        return $this;
    }

    /**
     * Set the line ending used in the CSV file.
     *
     * @param string $line_ending
     * @return $this
     */
    public function lineEnding(string $line_ending): static
    {
        $this->line_ending = $line_ending;
        return $this;
    }

    /**
     * Extract the data from the CSV file.
     *
     * @return Enumerable
     */
    public function extract(): Enumerable
    {
        return collect();
    }
}
