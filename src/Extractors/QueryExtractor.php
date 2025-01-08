<?php

namespace Windsor\Phetl\Extractors;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Enumerable;
use Illuminate\Support\Facades\DB;

class QueryExtractor extends Extractor
{
    protected string|QueryBuilder|EloquentBuilder $query;

    protected array $bindings = [];

    protected string $connection = null;


    /**
     * Create a new QueryExtractor instance.
     *
     * @param string|QueryBuilder|EloquentBuilder $query
     * @param array $bindings
     * @param string|null $connection
     */
    public function __construct(
        string|QueryBuilder|EloquentBuilder $query,
        array $bindings = [],
        string $connection = null
    ) {
        $this->query = $query;
        $this->bindings = $bindings;
        $this->connection = $connection;
    }

    public function extract(): Enumerable
    {
        return $this->getResults();
    }

    public function getResults(): Enumerable
    {
        if (! is_string($this->query)) {
            return $this->query->get();
        }

        if ($this->connection) {
            $results = DB::connection($this->connection)
                ->select($this->query, $this->bindings);
        }
        elseif (! $this->connection) {
            $results = DB::select($this->query, $this->bindings);
        }

        return collect($results);
    }
}