<?php

namespace Windsor\Phetl\Extractors;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Enumerable;
use Illuminate\Support\LazyCollection;

// TODO: utilize the PendingRequest::sink() method to save the response to a file, and process the file instead of the direct response.

class ApiExtractor extends BaseExtractor
{
    protected PendingRequest $request;

    protected Response $response;

    protected string $method = 'get';

    protected string $endpoint;

    protected array $query_string;

    protected array $post_data;

    protected string $accepted_content_type;

    protected \Closure $error_handler;

    protected \Closure $parser;

    protected ?string $data_path = null;

    protected bool $lazy = false;


    public function __construct($endpoint = null)
    {
        $this->endpoint = $endpoint;
        $this->request = new PendingRequest();
    }

    /**
     * Handle dynamic method calls into the request object.
     *
     * This gives the user the ability to call any method that exists on the request object; this is useful for setting headers, authentication, return type, etc.
     *
     *
     * We also intercept several methods:
     *
     * accept() and acceptJson() - We store this value to determine how to parse the response body.
     *
     * head(), patch(), put(), delete() - We block these methods from being used; These HTTP methods are not ordinarily used for retrieving data, which is beyond the scope of this system.
     *
     * pool() - Is blocked from use; Asynchronous requests are not supported.
     * * We may add support for asynchronous requests in the future if there is a demand for it.
     *
     * send() - Is blocked to restrict the user from using unsupported HTTP methods.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return void
     */
    public function __call($method, $parameters)
    {
        if (! method_exists($this->request, $method)) {
            throw new \BadMethodCallException("Method {$method} does not exist on the request object.");
        }

        if (in_array($method, ['head', 'patch', 'put', 'delete', 'pool', 'send'])) {
            throw new \BadMethodCallException("Method {$method} is blocked from use in the API extractor.");
        }

        // store the accepted content type
        if ($method == 'accept' || $method == 'acceptJson') {
            $this->accepted_content_type = $parameters[0];
        }

        $this->request->{$method}(...$parameters);
    }

    /**
     * Give the extractor a callable that will be used to parse the response body.
     * The callable should accept an instance of Illuminate\Http\Client\Response as an argument and return the data that will be processed, as an iterable.
     *
     * TODO: give user a way to retrieve other data from the response, like aggregates, pagination info, etc.
     * TODO: give user a way to handle errors in the response
     * TODO: give user a way to handle pagination
     * TODO: give user a way to handle rate limiting
     * TODO: give user a way to retrieve the response.
     *
     * Parse the response body with the given parser in order to extract the data
     *
     * @return void
     */
    public function parseBodyWith(callable $parser): static
    {
        if (! $parser instanceof \Closure) {
            $parser = \Closure::fromCallable($parser);
        }

        $this->parser = $parser;

        return $this;
    }

    /**
     * Using dot notation, set the data path that will be extracted from the response body.
     *
     * @return ApiExtractor
     */
    public function dataPath(string $key): static
    {
        $this->data_path = $key;

        return $this;
    }

    /**
     * Define the HTTP method that will be used for the API request.
     *
     * @param  string  $method
     * @return ApiExtractor
     */
    public function method($method): static
    {
        $this->method = strtolower($method);

        return $this;
    }

    /**
     * Define the URL for the API request, and optionally set query parameters.
     *
     * This method overrides the "get()" method on the request class, which actually sends the request.
     *
     * @param  mixed  $url
     * @param  mixed  $query
     * @return ApiExtractor
     */
    public function get($url, $query = []): static
    {
        $this->endpoint = $url;
        $this->query_string = $query;

        return $this;
    }

    /**
     * Define the URL for the API request, and optionally set data to be sent with the request.
     *
     * This method overrides the "post()" method on the request class, which actually sends the request.
     *
     * @param  mixed  $url
     * @param  mixed  $data
     * @return ApiExtractor
     */
    public function post($url, $data = []): static
    {
        $this->method = 'post';
        $this->endpoint = $url;
        $this->post_data = $data;

        return $this;
    }

    /**
     * Immediately execute the given callback if there was an error in the response.
     *
     * @return ApiExtractor
     */
    public function onError(callable $callback): static
    {
        if (! $callback instanceof \Closure) {
            $callback = \Closure::fromCallable($callback);
        }

        $this->error_handler = $callback;

        return $this;
    }

    /**
     * Set the extractor to return a LazyCollection instead of a Collection.
     *
     * @return ApiExtractor
     */
    public function lazy(): static
    {
        $this->lazy = true;

        return $this;
    }

    /**
     * Extract the data from the API response.
     */
    public function extract(): Enumerable
    {
        $this->response = $this->sendRequest();

        if ($this->parser) {
            $data = call_user_func($this->parser, $this->response);
            $this->validateParserReturnType($data);
        }
        else {
            $data = $this->response->collect($this->data_path);
        }

        if ($this->lazy) {
            $data = $data->lazy();
        }

        return $data;
    }

    /**
     * Validate that the parser returns an instance of Collection or LazyCollection.
     *
     * @param  mixed  $data
     */
    protected function validateParserReturnType($data): void
    {
        if ($data instanceof Enumerable) {
            return;
        }

        $self_class = self::class;
        $collection_class = Collection::class;
        $lazy_collection_class = LazyCollection::class;

        throw new \RuntimeException("The callable passed to {$self_class}::parseBodyWith() must return an instance of {$collection_class} or {$lazy_collection_class}.");
    }

    /**
     * Send the request to the API endpoint.
     */
    protected function sendRequest(): Response
    {
        $extra_data = match ($this->method) {
            'get' => $this->query_string,
            'post' => $this->post_data,
            default => [],
        };

        $response = $this->request->{$this->method}($this->endpoint, $extra_data);

        if ($this->error_handler) {
            $response->onError($response);
        }

        $response->throwUnlessSuccessful();

        return $this->request->{$this->method}($this->endpoint, $extra_data);
    }
}
