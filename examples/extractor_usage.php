<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Windsor\Phetl\Facades\Extract;

// to prevent errors, we'll define a dummy model
class Book extends Model {}


// Use the CsvExtractor to extract data from a CSV or Excel file


// the Extract facade is used to create extractors

// API Extractor
$api_extractor = Extract::fromApi()
    ->endpoint('https://api.example.com/api/v2/books');

// or
$api_extractor = Extract::fromApi('https://api.example.com/api/v2/books');



// CSV Extractor
$csv_extractor = Extract::fromCsv()
    ->path('path/to/file.csv');

// or
$csv_extractor = Extract::fromCsv('path/to/file.csv');



// Query Extractor
$query_extractor = Extract::fromQuery(DB::table('books'));

// or using an eloquent query
$query_extractor = Extract::fromQuery(Book::all());

// or using raw SQL
$query_extractor = Extract::fromQuery()
    ->raw('SELECT * FROM books');

// or
$query_extractor = Extract::fromQuery('SELECT * FROM books');

// or using a closure to build a query
$query_extractor = Extract::fromQuery(function () {
    return DB::table('books')
        ->select(
            'title',
            'author',
            'published'
        )
        ->join('authors', 'books.author_id', '=', 'authors.id')
        ->where('published', true)
        ->orderBy('title');
});







// API Extractor

// Set the endpoint
$api_extractor->endpoint('https://api.example.com/api/v2/books');

/** any method that belongs to the Illuminate\Http\Client\Request class can be called on the extractor to configure the request.
 *
 * The methods that are usually used for executing the request have been augmented so that they do not execute the request.
 *
 * (Laravel Docs)[https://laravel.com/docs/11.x/http-client#making-requests]
 */
$api_extractor->acceptsJson()
    ->withQueryParameters([
        'unselect' => ['created_at', 'updated_at'],
        'filter' => "created_at gt '2021-01-01'",
    ])
    ->retry(3, 1000)
    ->withAuthToken(config('my-api-key'));

// Set the request method
$api_extractor->method('get');

// assign a closure that will be used to parse the response
$api_extractor->parseBodyWith(function ($response) {
    return $response['data'];
});

// set the path to the data in the response
$api_extractor->dataPath('data.books');

// set a callback that will be called if there is a client or server error
$api_extractor->onError(function ($response) {
    // handle the error
});


// CSV Extractor

// Set the path to the file
$csv_extractor->path('path/to/file.csv');

/**
 * all methods that belong to the Spatie\SimpleExcel\SimpleExcelReader class can be called on the extractor to configure the extractor.
 *
 * @see Spatie\SimpleExcel\SimpleExcelReader for more methods
 * (GitHub Page)[https://github.com/spatie/simple-excel]
 */
$csv_extractor->useDelimiter(',')
    ->useFieldEnclosure('"')
    ->useHeaders(['title', 'author', 'published'])
    ->noHeaderRow()
    ->preserveEmptyRows();



// Query Extractor

// * The `fromQuery` method can be used to create a query extractor from a query builder (database or eloquent), a raw SQL query, or a closure that returns a query builder (database or eloquent). It can also be left empty, and the query can be set later using the `query` method or the `raw` method.

// configuring the query extractor to use a raw SQL query can be done through the constructor, or the `raw` method
$query_extractor = Extract::fromQuery('SELECT * FROM books');
$query_extractor->raw('SELECT * FROM books');

// * using the `raw` method after the extractor has been given a query will result in the query being overwritten

// when configuring a SQL string query, you may optionally also pass an array of bindings, and a connection name (this applies to both the constructor and the `raw` method)
$query_extractor->raw(
    'SELECT * FROM books WHERE published = ?',
    [true],
    'mysql'
);

// using the Database Query Builder
$query_extractor->query(
    DB::table('books')
        ->select('title', 'author', 'published')
        ->where('published', true)
        ->orderBy('title')
);

// using the Eloquent Query Builder
$query_extractor->query(
    Book::query()
        ->select('title', 'author', 'published')
        ->where('published', true)
        ->orderBy('title')
);

// using a closure to build a complex and long query
$query_extractor->query(function () {
    return DB::table('books')
        ->select('title', 'author', 'published')
        // ... lots of other query methods ...
        ->join('authors', 'books.author_id', '=', 'authors.id')
        ->where('published', true)
        ->orderBy('title');
});


/**
 * Lifecycle Hooks
 *
 * Extractors have 2 lifecycle hooks that can be used to run code before or after the data is extracted.
 * These hooks can be set using the `beforeExtraction` and `afterExtraction` methods.
 * Both methods accept a callable that will be executed when the hook is triggered. And the callable will have access to the Extractor instance.
 *
 * The `afterExtraction` method also passes the extracted data to the callable.
 *
 * These lifecycle hooks may be very useful in logging, debugging, or other things like writing to the console output.
 */

// setting a hook to run before the data is extracted
$api_extractor->beforeExtraction(function ($extractor) {
    // do something before the data is extracted
    dump($extractor->getHeaders());
});

// setting a hook to run after the data is extracted
$api_extractor->afterExtraction(function ($extractor, $data) {
    // do something after the data is extracted
    $records_count = $data->count();
    Log::info('Data extracted from API', ['records_count' => $records_count]);
});