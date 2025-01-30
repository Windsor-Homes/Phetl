<?php

use Windsor\Phetl\Utils\Conditions\WhereColumn;

// test that WhereColumn can resolve the column value(s) from the row
it('can resolve single values from a given column', function () {
    $row = [
        'name' => 'John Doe',
        'age' => 30,
    ];
    $condition = new WhereColumn('name', '=', 'age');
    $result = $condition->resolveColumnValue($row);

    expect($result)->toBe(30);
});

it('can resolve an array of values from an array of columns', function () {
    $row = [
        'name' => 'John Doe',
        'age' => 30,
        'spouse_age' => 28,
    ];
    $condition = new WhereColumn('name', 'between', ['age', 'spouse_age']);
    $result = $condition->resolveColumnValue($row);

    expect($result)->toBe([30, 28]);
});

