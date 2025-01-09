<?php

namespace Windsor\Phetl;

use Windsor\Phetl\Transformers\Transformer;
use Windsor\Phetl\Concerns\HasLifecycleHooks;

class TransformationPipeline
{
    use HasLifecycleHooks;

    /**
     * @var array
     */
    protected $transformers = [];

    /**
     * @return $this
     */
    public function addTransformer(callable|Transformer $transformer)
    {
        $this->transformers[] = $transformer;

        return $this;
    }

    /**
     * @return array
     */
    public function getTransformers()
    {
        return $this->transformers;
    }

    protected function transform($data)
    {
        $this->runHooks('start-transformations', $this, $data);

        foreach ($this->transformers as $transformer) {
            $this->runHooks('before-transformer', $transformer, $data);

            $data = $transformer->transformDataset($data);

            $this->runHooks('after-transformer', $transformer, $data);
        }

        $this->runHooks('end-transformations', $this, $data);

        return $data;
    }



    /**
     * Rename headers
     *
     * @param array<string, string> $headers Array of old and new header names
     * @return void
     */
    public function renameHeaders(array $headers)
    {
        $this->addTransformer(function ($row) use ($headers) {
            foreach ($headers as $old => $new) {
                if (isset($row[$old])) {
                    $row[$new] = $row[$old];
                    unset($row[$old]);
                }
            }

            return $row;
        });

        return $this;
    }

    /**
     * Convert headers to snake case
     *
     * @return void
     */
    public function headersToSnakeCase()
    {
        $this->addTransformer(function ($row) {
            $new_row = [];
            foreach ($row as $key => $value) {
                $new_row[snake_case($key)] = $value;
            }

            return $new_row;
        });

        return $this;
    }

    public function titleCase(string ...$columns)
    {
        $this
    }
}
