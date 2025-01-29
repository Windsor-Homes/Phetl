<?php

use Windsor\Phetl\Utils\Conditions\Where;

// generate tests for the Utils\Conditions\Where class

describe('"Equal" Comparisons', function () {
    it('can evaluate correctly', function () {
        $condition = new Where('num', '==', '3');

        expect($condition->check(['num' => '3']))->toBeTrue();
        expect($condition->check(['num' => 3]))->toBeTrue();
        expect($condition->check(['num' => 'foo']))->toBeFalse();
    });

    it('can be negated', function () {
        $condition = new Where('num', '==', 3, 'and', true);

        expect($condition->check(['num' => 3]))->toBeFalse();
        expect($condition->check(['num' => "3"]))->toBeFalse();
        expect($condition->check(['num' => 2]))->toBeTrue();
    });

    it('is not affected by the conjunction parameter', function () {
        $or = new Where('num', '==', 3, 'or');
        $and = new Where('num', '==', 3, 'and');

        expect($or->check(['num' => 3]))->toBeTrue();
        expect($and->check(['num' => 3]))->toBeTrue();
    });
});

describe('"Identical" Comparisons', function () {
    it('can evaluate correctly', function () {
        $condition = new Where('num', '===', 3);

        expect($condition->check(['num' => 3]))->toBeTrue();
        expect($condition->check(['num' => '3']))->toBeFalse();
    });

    it('can be negated', function () {
        $condition = new Where('num', '===', 3, 'and', true);

        expect($condition->check(['num' => 3]))->toBeFalse();
        expect($condition->check(['num' => '3']))->toBeTrue();
    });

    it('is not affected by the conjunction parameter', function () {
        $or = new Where('num', '===', 3, 'or');
        $and = new Where('num', '===', 3, 'and');

        expect($or->check(['num' => 3]))->toBeTrue();
        expect($and->check(['num' => 3]))->toBeTrue();
    });
});

describe('"Not Equal" Comparisons', function () {
    it('can evaluate correctly', function () {
        $condition = new Where('num', '!=', 3);

        expect($condition->check(['num' => 2]))->toBeTrue();
        expect($condition->check(['num' => 3]))->toBeFalse();
        expect($condition->check(['num' => "3"]))->toBeFalse();
    });

    it('can be negated', function () {
        $condition = new Where('num', '!=', 3, 'and', true);

        expect($condition->check(['num' => 3]))->toBeTrue();
        expect($condition->check(['num' => 2]))->toBeFalse();
    });

    it('is not affected by the conjunction parameter', function () {
        $or = new Where('num', '!=', 3, 'or');
        $and = new Where('num', '!=', 3, 'and');

        expect($or->check(['num' => 2]))->toBeTrue();
        expect($and->check(['num' => 2]))->toBeTrue();
    });
});

describe('"Not Identical" Comparisons', function () {
    it('can evaluate correctly', function () {
        $condition = new Where('num', '!==', 3);

        expect($condition->check(['num' => 2]))->toBeTrue();
        expect($condition->check(['num' => '3']))->toBeTrue();
        expect($condition->check(['num' => 3]))->toBeFalse();
    });

    it('can be negated', function () {
        $condition = new Where('num', '!==', 3, 'and', true);

        expect($condition->check(['num' => 3]))->toBeTrue();
        expect($condition->check(['num' => '3']))->toBeFalse();
    });

    it('is not affected by the conjunction parameter', function () {
        $or = new Where('num', '!==', 3, 'or');
        $and = new Where('num', '!==', 3, 'and');

        expect($or->check(['num' => 2]))->toBeTrue();
        expect($and->check(['num' => 2]))->toBeTrue();
    });
});

describe('"Greater Than" Comparisons', function () {
    it('can evaluate correctly', function () {
        $condition = new Where('num', '>', 3);

        expect($condition->check(['num' => 4]))->toBeTrue();
        expect($condition->check(['num' => 3]))->toBeFalse();
        expect($condition->check(['num' => 2]))->toBeFalse();
    });

    it('can be negated', function () {
        $condition = new Where('num', '>', 3, 'and', true);

        expect($condition->check(['num' => 4]))->toBeFalse();
        expect($condition->check(['num' => 3]))->toBeTrue();
    });

    it('is not affected by the conjunction parameter', function () {
        $or = new Where('num', '>', 3, 'or');
        $and = new Where('num', '>', 3, 'and');

        expect($or->check(['num' => 4]))->toBeTrue();
        expect($and->check(['num' => 4]))->toBeTrue();
    });
});

describe('"Greater Than Or Equal" Comparisons', function () {
    it('can evaluate correctly', function () {
        $condition = new Where('num', '>=', 3);

        expect($condition->check(['num' => 4]))->toBeTrue();
        expect($condition->check(['num' => 3]))->toBeTrue();
        expect($condition->check(['num' => 2]))->toBeFalse();
    });

    it('can be negated', function () {
        $condition = new Where('num', '>=', 3, 'and', true);

        expect($condition->check(['num' => 4]))->toBeFalse();
        expect($condition->check(['num' => 3]))->toBeFalse();
    });

    it('is not affected by the conjunction parameter', function () {
        $or = new Where('num', '>=', 3, 'or');
        $and = new Where('num', '>=', 3, 'and');

        expect($or->check(['num' => 4]))->toBeTrue();
        expect($and->check(['num' => 4]))->toBeTrue();
    });
});

describe('"Less Than" Comparisons', function () {
    it('can evaluate correctly', function () {
        $condition = new Where('num', '<', 3);

        expect($condition->check(['num' => 2]))->toBeTrue();
        expect($condition->check(['num' => 3]))->toBeFalse();
        expect($condition->check(['num' => 4]))->toBeFalse();
    });

    it('can be negated', function () {
        $condition = new Where('num', '<', 3, 'and', true);

        expect($condition->check(['num' => 2]))->toBeFalse();
        expect($condition->check(['num' => 3]))->toBeTrue();
    });

    it('is not affected by the conjunction parameter', function () {
        $or = new Where('num', '<', 3, 'or');
        $and = new Where('num', '<', 3, 'and');

        expect($or->check(['num' => 2]))->toBeTrue();
        expect($and->check(['num' => 2]))->toBeTrue();
    });
});

describe('"Less Than Or Equal" Comparisons', function () {
    it('can evaluate correctly', function () {
        $condition = new Where('num', '<=', 3);

        expect($condition->check(['num' => 2]))->toBeTrue();
        expect($condition->check(['num' => 3]))->toBeTrue();
        expect($condition->check(['num' => 4]))->toBeFalse();
    });

    it('can be negated', function () {
        $condition = new Where('num', '<=', 3, 'and', true);

        expect($condition->check(['num' => 2]))->toBeFalse();
        expect($condition->check(['num' => 3]))->toBeFalse();
    });

    it('is not affected by the conjunction parameter', function () {
        $or = new Where('num', '<=', 3, 'or');
        $and = new Where('num', '<=', 3, 'and');

        expect($or->check(['num' => 2]))->toBeTrue();
        expect($and->check(['num' => 2]))->toBeTrue();
    });
});

describe('"In" Comparisons', function () {
    it('can evaluate correctly', function () {
        $condition = new Where('num', 'in', [1, 2, 3]);

        expect($condition->check(['num' => 1]))->toBeTrue();
        expect($condition->check(['num' => 2]))->toBeTrue();
        expect($condition->check(['num' => 3]))->toBeTrue();
        expect($condition->check(['num' => 4]))->toBeFalse();
        expect($condition->check(['num' => 0]))->toBeFalse();
    });

    it('can be negated', function () {
        $condition = new Where('num', 'in', [1, 2, 3], 'and', true);

        expect($condition->check(['num' => 1]))->toBeFalse();
        expect($condition->check(['num' => 2]))->toBeFalse();
        expect($condition->check(['num' => 3]))->toBeFalse();
        expect($condition->check(['num' => 4]))->toBeTrue();
        expect($condition->check(['num' => 0]))->toBeTrue();
    });

    it('is not affected by the conjunction parameter', function () {
        $or = new Where('num', 'in', [1, 2, 3], 'or');
        $and = new Where('num', 'in', [1, 2, 3], 'and');

        expect($or->check(['num' => 4]))->toBeFalse();
        expect($and->check(['num' => 4]))->toBeFalse();
    });
});

describe('"Between" Comparisons', function () {
    it('can evaluate correctly', function () {
        $condition = new Where('num', 'between', [1, 3]);

        expect($condition->check(['num' => 1]))->toBeTrue();
        expect($condition->check(['num' => 2]))->toBeTrue();
        expect($condition->check(['num' => 3]))->toBeTrue();
        expect($condition->check(['num' => 4]))->toBeFalse();
        expect($condition->check(['num' => 0]))->toBeFalse();
    });

    it('can be negated', function () {
        $condition = new Where('num', 'between', [1, 3], 'and', true);

        expect($condition->check(['num' => 1]))->toBeFalse();
        expect($condition->check(['num' => 2]))->toBeFalse();
        expect($condition->check(['num' => 3]))->toBeFalse();
        expect($condition->check(['num' => 4]))->toBeTrue();
        expect($condition->check(['num' => 0]))->toBeTrue();
    });

    it('is not affected by the conjunction parameter', function () {
        $or = new Where('num', 'between', [1, 3], 'or');
        $and = new Where('num', 'between', [1, 3], 'and');

        expect($or->check(['num' => 4]))->toBeFalse();
        expect($and->check(['num' => 4]))->toBeFalse();
    });
});

describe('"instanceof" Comparisons', function () {
    it('can evaluate correctly', function () {
        $condition = new Where('num', 'instanceof', 'stdClass');

        expect($condition->check(['num' => new \stdClass()]))->toBeTrue();
        expect($condition->check(['num' => new \Exception()]))->toBeFalse();
    });

    it('can be negated', function () {
        $condition = new Where('num', 'instanceof', 'stdClass', 'and', true);

        expect($condition->check(['num' => new \stdClass()]))->toBeFalse();
        expect($condition->check(['num' => new \Exception()]))->toBeTrue();
    });

    it('is not affected by the conjunction parameter', function () {
        $or = new Where('num', 'instanceof', 'stdClass', 'or');
        $and = new Where('num', 'instanceof', 'stdClass', 'and');

        expect($or->check(['num' => new \Exception()]))->toBeFalse();
        expect($and->check(['num' => new \Exception()]))->toBeFalse();
    });
});
