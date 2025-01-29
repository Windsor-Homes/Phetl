<?php

use Windsor\Phetl\Utils\Conditions\WhereColumn;

// test that WhereColumn can resolve the column value(s) from the row
it('should resolve column value from row', function () {
    $row = [
        'name' => 'John Doe',
        'age' => 30,
        'email' => ''
    ];

    $condition = new WhereColumn('name', '=', 'age');

    expect($condition->resolveColumnValue($row))->toBe(30);
});

it('should resolve multiple column values from row', function () {
    $row = [
        'name' => 'John Doe',
        'age' => 30,
        'spouse_age' => 28,
        'email' => '',
    ];

    $condition = new WhereColumn('name', 'between', ['age', 'spouse_age']);

    expect($condition->resolveColumnValue($row))->toBe([30, 28]);
});

