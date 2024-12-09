<?php

use Windsor\Phetl\Facades\Extract;
use Windsor\Phetl\Facades\Transform;

$api_extractor = Extract::fromApi()
    ->endpoint('https://api.example.com/api/v2/books')
    ->acceptsJson()
    ->withAuthToken(config('my-api-key'))
    ->withParameters([
        'unselect' => ['created_at', 'updated_at'],
        'filter' => "created_at gt '2021-01-01'",
    ])
    ->parseBodyWith(function ($response) {
        return $response['data'];
    });

$csv_extractor = Extract::fromCsv()
    ->path('path/to/file.csv')
    ->delimiter(',')
    ->enclosure('"')
    ->escape('\\')
    ->headerRow(true)
    ->select([
        'book_title',
        'author_first_name',
        'author_last_name',
        'isbn',
        'genre',
        'publishing_company',
        'publishing_date',
    ]);

$query_extractor = Extract::fromQuery()
    ->query($query);

$merged_extractor = Extract::concat($api_extractor, $csv_extractor);

$extractor = $extract->join(
    $query_extractor,
    $merged_extractor,
    'isbn',
);

$transformer = Transform::renameHeaders([
    'book_title' => 'title',
])
    ->headersToSnakeCase()
    ->defaultValues([
        'genre' => 'Fiction',
    ])
    ->casts([
        'publishing_date' => Carbon::class,
        'isbn' => 'int',
    ])
    ->titleCase('title')
    ->addColumns([
        'author_name' => function ($row) {
            return $row['author_first_name'].' '.$row['author_last_name'];
        },
        'reading_level' => '8',
    ])
    ->dropColumns('author_first_name', 'author_last_name')
    ->unpackColumn(
        'isbn',
        ['isbn_10', 'isbn_13'],
        fn ($value) => explode(',', $value)
    )
    ->mapForeignKey('publishing_company', function ($value) {
        return PublishingCompany::firstOrCreate(['name' => $value])->id;
    })
    ->mapForeignKeyToDatabase('genre', 'genres', 'name', 'id')
    ->renameColumns([
        'publishing_company' => 'publisher_id',
        'genre' => 'genre_id',
    ])
    ->unique('isbn_13')
    ->where('publishing_date', '>', now()->subYears(5))
    ->validate([
        'title' => 'required',
        'author_name' => 'required',
        'isbn_10' => 'required|digits:10',
        'isbn_13' => 'required|digits:13',
        'publishing_date' => 'required|date',
        'reading_level' => 'required|numeric',
    ]);
