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
        string|callable|QueryBuilder|EloquentBuilder $query = null,
        array $bindings = [],
        string $connection = null
    ) {
        if ($query === null) {
            return;
        }

        if (is_callable($query)) {
            $query = $this->getQueryBuilderFromCallable($query);
        }

        $this->query = $query;
        $this->bindings = $bindings;
        $this->connection = $connection;
    }

    public function query(
        callable|QueryBUilder|EloquentBuilder $query
    ): static {
        $this->query = $query;

        if (is_callable($query)) {
            $this->query = $this->getQueryBuilderFromCallable($query);
        }

        return $this;
    }

    public function raw(
        string $query,
        array $bindings = [],
        string $connection = null
    ): static {
        $this->query = $query;
        $this->bindings = $bindings;
        $this->connection = $connection;

        return $this;
    }

    public function extract(): Enumerable
    {
        if (is_string($this->query)) {
            return $this->getResultsFromRawQuery();
        }

        return $this->query->get();
    }

    /**
     * Get the results from a raw query.
     *
     * @return \Illuminate\Support\Enumerable
     */
    protected function getResultsFromRawQuery(): Enumerable
    {
        if ($this->connection) {
            $results = DB::connection($this->connection)
                ->select($this->query, $this->bindings);
        }
        elseif (! $this->connection) {
            $results = DB::select($this->query, $this->bindings);
        }

        return collect($results);
    }

    /**
     * Determine if the query is a builder instance.
     *
     * @param mixed $query
     * @return bool
     */
    protected function queryIsBuilder($query): bool
    {
        return $this->query instanceof QueryBuilder
            || $this->query instanceof EloquentBuilder;
    }

    /**
     * Get a Query|Eloquent Builder instance from a callable.
     *
     * @return QueryBuilder|EloquentBuilder
     */
    protected function getQueryBuilderFromCallable(
        callable $callback
    ): QueryBuilder|EloquentBuilder {

        if (! $callback instanceof \Closure) {
            $callback = \Closure::fromCallable($callback);
        }

        $query = call_user_func($callback);

        if (! $this->queryIsBuilder($query)) {
            throw new \RuntimeException(
                "The callable passed to " . self::class . " must return an instance of " . QueryBuilder::class . " or " . EloquentBuilder::class
            );
        }

        return $query;
    }
}