<?php

use \Mockery;
use Windsor\Phetl\Utils\Conditions\NestedCondition;
use Windsor\Phetl\Utils\Conditions\Where;
use Windsor\Phetl\Utils\Conditions\WhereColumn;

function getMockCondition(string $conjunction, bool $result, $call_count = null)
{
    $mock = Mockery::mock(
        Where::class,
        ['Foo', '=', 'Bar', $conjunction]
    );
    $expectation = $mock->shouldReceive('check')
        ->with(test()->row)
        ->andReturn($result);

    if ($call_count > 0) {
        $expectation->times($call_count);
    }
    elseif ($call_count === 0) {
        $expectation->never();
    }

    return $mock;
}

beforeEach(function () {
    $this->row = [
        'name' => 'John Doe',
        'age' => 30,
        'sales_score' => 80,
        'spouse_age' => 28,
        'team_id' => 8,
        'team_sales_score' => 90,
    ];
});


it('can evaluate basic "AND" conditions', function () {
    $nest1 = NestedCondition::make([
        getMockCondition('and', true),
        getMockCondition('and', true),
    ]);
    $expr1 = (true && true);

    $nest2 = NestedCondition::make([
        getMockCondition('and', true),
        getMockCondition('and', false),
    ]);
    $expr2 = (true && false);

    $nest3 = NestedCondition::make([
        getMockCondition('and', false),
        getMockCondition('and', false, 0),
    ]);
    $expr3 = (false && false);

    expect($nest1->check($this->row))->toBe($expr1);
    expect($nest2->check($this->row))->toBe($expr2);
    expect($nest3->check($this->row))->toBe($expr3);
});

it('can evaluate basic "OR" conditions', function () {
    $nest1 = NestedCondition::make([
        getMockCondition('or', true),
        getMockCondition('or', true),
    ]);
    $expr1 = (true || true);

    $nest2 = NestedCondition::make([
        getMockCondition('or', true),
        getMockCondition('or', false),
    ]);
    $expr2 = (true || false);

    $nest3 = NestedCondition::make([
        getMockCondition('or', false),
        getMockCondition('or', false),
    ]);
    $expr3 = (false || false);

    expect($nest1->check($this->row))->toBe($expr1);
    expect($nest2->check($this->row))->toBe($expr2);
    expect($nest3->check($this->row))->toBe($expr3);
});

it('ignores the conjunction of the first condition', function () {
    $nest1 = NestedCondition::make([
        getMockCondition('or', true),
        getMockCondition('and', true),
    ]);
    $expr1 = (true && true);

    $nest2 = NestedCondition::make([
        getMockCondition('and', true),
        getMockCondition('or', true),
    ]);
    $expr2 = (true || true);

    expect($nest1->check($this->row))->toBe($expr1);
    expect($nest2->check($this->row))->toBe($expr2);
});

it('short-circuits the evaluation of "AND" conditions', function () {
    $nest = NestedCondition::make([
        getMockCondition('and', false, 1),
        getMockCondition('and', true, 0),
    ]);
    $expr = (false && true);

    expect($nest->check($this->row))->toBe($expr);
});

it('short-circuits the evaluation of "OR" conditions', function () {
    $nest = NestedCondition::make([
        getMockCondition('or', true, 1),
        getMockCondition('or', false, 0),
    ]);
    $expr = (true || false);

    expect($nest->check($this->row))->toBe($expr);
});

it('can evaluate chained "AND" conditions', function () {
    $nest1 = NestedCondition::make([
        getMockCondition('and', true),
        getMockCondition('and', true),
        getMockCondition('and', true),
    ]);
    $expr1 = (true && true && true);

    $nest2 = NestedCondition::make([
        getMockCondition('and', true),
        getMockCondition('and', true),
        getMockCondition('and', false),
    ]);
    $expr2 = (true && true && false);

    $nest3 = NestedCondition::make([
        getMockCondition('and', true),
        getMockCondition('and', false),
        getMockCondition('and', true),
    ]);
    $expr3 = (true && false && true);

    $nest4 = NestedCondition::make([
        getMockCondition('and', false),
        getMockCondition('and', true),
        getMockCondition('and', true),
    ]);
    $expr4 = (false && true && true);

    $nest5 = NestedCondition::make([
        getMockCondition('and', false),
        getMockCondition('and', false),
        getMockCondition('and', false),
    ]);
    $expr5 = (false && false && false);

    expect($nest1->check($this->row))->toBe($expr1);
    expect($nest2->check($this->row))->toBe($expr2);
    expect($nest3->check($this->row))->toBe($expr3);
    expect($nest4->check($this->row))->toBe($expr4);
    expect($nest5->check($this->row))->toBe($expr5);
});

it('can evaluate chained "OR" conditions', function () {
    $nest1 = NestedCondition::make([
        getMockCondition('or', true),
        getMockCondition('or', true),
        getMockCondition('or', true),
    ]);
    $expr1 = (true || true || true);

    $nest2 = NestedCondition::make([
        getMockCondition('or', true),
        getMockCondition('or', true),
        getMockCondition('or', false),
    ]);
    $expr2 = (true || true || false);

    $nest3 = NestedCondition::make([
        getMockCondition('or', true),
        getMockCondition('or', false),
        getMockCondition('or', true),
    ]);
    $expr3 = (true || false || true);

    $nest4 = NestedCondition::make([
        getMockCondition('or', false),
        getMockCondition('or', true),
        getMockCondition('or', true),
    ]);
    $expr4 = (false || true || true);

    $nest5 = NestedCondition::make([
        getMockCondition('or', false),
        getMockCondition('or', false),
        getMockCondition('or', false),
    ]);
    $expr5 = (false || false || false);

    expect($nest1->check($this->row))->toBe($expr1);
    expect($nest2->check($this->row))->toBe($expr2);
    expect($nest3->check($this->row))->toBe($expr3);
    expect($nest4->check($this->row))->toBe($expr4);
    expect($nest5->check($this->row))->toBe($expr5);
});

it('can evaluate basic mixed "AND" and "OR" conditions', function () {
    $nest1 = NestedCondition::make([
        getMockCondition('and', true),
        getMockCondition('and', true),
        getMockCondition('or', false),
    ]);
    $expr1 = (true && true || false);

    $nest2 = NestedCondition::make([
        getMockCondition('or', true),
        getMockCondition('or', false),
        getMockCondition('and', true),
    ]);
    $expr2 = (true || false && true);

    expect($nest1->check($this->row))->toBe($expr1);
    expect($nest2->check($this->row))->toBe($expr2);
});

it('does not handle operator precedence correctly', function () {
    $nest = NestedCondition::make([
        getMockCondition('and', true),
        getMockCondition('or', false),
        getMockCondition('and', false),
    ]);
    $expr = (true || false && false);

    expect($nest->check($this->row))->toBeFalse();
    expect($expr)->toBeTrue();
    expect($nest->check($this->row))->not->toBe($expr);
});

it('can evaluate "AND" nested conditions', function () {
    $nest1 = NestedCondition::make([
        getMockCondition('and', true),
        NestedCondition::make([
            getMockCondition('and', true),
            getMockCondition('and', true),
        ]),
    ]);
    $expr1 = (true && (true && true));

    $nest2 = NestedCondition::make([
        getMockCondition('and', true),
        NestedCondition::make([
            getMockCondition('and', true),
            getMockCondition('and', false),
        ]),
    ]);
    $expr2 = (true && (true && false));

    $nest3 = NestedCondition::make([
        getMockCondition('and', true),
        NestedCondition::make([
            getMockCondition('and', false),
            getMockCondition('or', true),
        ]),
    ]);
    $expr3 = (true && (false || true));

    expect($nest1->check($this->row))->toBe($expr1);
    expect($nest2->check($this->row))->toBe($expr2);
    expect($nest3->check($this->row))->toBe($expr3);
});

it('can evaluate "OR" nested conditions', function () {
    $nest = NestedCondition::make([
        getMockCondition('and', true),
        NestedCondition::make([
            getMockCondition('and', false),
            getMockCondition('and', false),
        ], 'or'),
    ]);
    $expr = (true || (false && false));

    expect($nest->check($this->row))->toBe($expr);
});

it('can evaluate multiple nested conditions', function () {
    $nest1 = NestedCondition::make([
        NestedCondition::make([
            getMockCondition('and', true),
            getMockCondition('and', false),
        ]),
        NestedCondition::make([
            getMockCondition('and', true),
            getMockCondition('and', true),
        ], 'or'),
    ]);
    $expr1 = ((true && false) || (true && true));

    $nest2 = NestedCondition::make([
        NestedCondition::make([
            getMockCondition('and', true),
            getMockCondition('and', false),
        ]),
        NestedCondition::make([
            getMockCondition('and', true),
            getMockCondition('and', true),
        ]),
    ]);
    $expr2 = ((true && false) && (true && true));

    $nest3 = NestedCondition::make([
        NestedCondition::make([
            getMockCondition('and', false),
            getMockCondition('or', true),
        ]),
        NestedCondition::make([
            getMockCondition('and', true),
            getMockCondition('and', true),
        ]),
    ]);
    $expr3 = ((false || true) && (true && true));

    expect($nest1->check($this->row))->toBe($expr1);
    expect($nest2->check($this->row))->toBe($expr2);
    expect($nest3->check($this->row))->toBe($expr3);
});

it('can evaluate deeply nested conditions', function () {
    $nest = NestedCondition::make([
        NestedCondition::make([
            getMockCondition('and', true),
            NestedCondition::make([
                getMockCondition('and', false),
                getMockCondition('or', true),
            ]),
        ]),
        NestedCondition::make([
            getMockCondition('and', true),
            getMockCondition('and', true),
        ]),
    ]);
    $expr = ((true && (false || true)) && (true && true));

    expect($nest->check($this->row))->toBe($expr);
});

it('can negate the result of the conditions', function () {
    $nest1 = NestedCondition::make([
        getMockCondition('and', true),
        getMockCondition('and', true),
    ], negate: true);
    $expr1 = !(true && true);

    $nest2 = NestedCondition::make([
        getMockCondition('and', true),
        getMockCondition('and', false),
    ], negate: true);
    $expr2 = !(true && false);

    $nest3 = NestedCondition::make([
        getMockCondition('and', false),
        getMockCondition('or', true),
    ], negate: true);
    $expr3 = !(false || true);

    expect($nest1->check($this->row))->toBe($expr1);
    expect($nest2->check($this->row))->toBe($expr2);
    expect($nest3->check($this->row))->toBe($expr3);
});
