<?php

use Windsor\Phetl\Utils\Conditions\Builder;
use Windsor\Phetl\Utils\Conditions\NestedCondition;
use Windsor\Phetl\Utils\Conditions\Where;
use Windsor\Phetl\Utils\Conditions\WhereColumn;

describe('Basic Usage', function () {
    it('can add a simple condition', function () {
        $builder = Builder::make()->where('field', '==', 'value');
        $conditions = $builder->getConditions();

        expect($conditions)->toEqual([
            new Where('field', '==', 'value'),
        ]);
    });

    it('will use "==" as the default operator', function () {
        $builder = Builder::make()->where('field', 'value');
        $conditions = $builder->getConditions();

        expect($conditions)->toEqual([
            new Where('field', '==', 'value'),
        ]);
    });

    it('will accept "=" and "==" as the same operator', function () {
        $builder = Builder::make()
            ->where('field1', '=', 'value1')
            ->where('field2', '==', 'value2');
        $conditions = $builder->getConditions();

        expect($conditions)->toEqual([
            new Where('field1', '==', 'value1'),
            new Where('field2', '==', 'value2'),
        ]);
    });

    it('will accept "!=" and "<>" as the same operator', function () {
        $builder = Builder::make()
            ->where('field1', '!=', 'value1')
            ->where('field2', '<>', 'value2');
        $conditions = $builder->getConditions();

        expect($conditions)->toEqual([
            new Where('field1', '!=', 'value1'),
            new Where('field2', '!=', 'value2'),
        ]);
    });

    it('can add multiple conditions', function () {
        $builder = Builder::make()->where('field1', '=', 'value1');
        $builder->where('field2', '>', 'value2');
        $conditions = $builder->getConditions();

        expect($conditions)->toEqual([
            new Where('field1', '==', 'value1'),
            new Where('field2', '>', 'value2'),
        ]);
    });

    it('can add with basic operators', function () {
        $builder = Builder::make()
            ->where('field1', '=', 'value1')
            ->where('field2', '!=', 'value2')
            ->where('field3', '>', 'value3')
            ->where('field4', '>=', 'value4')
            ->where('field5', '<', 'value5')
            ->where('field6', '<=', 'value6')
            ->whereStrict('field7', 'value7');

        $conditions = $builder->getConditions();

        expect($conditions)->toHaveCount(7);
        expect($conditions[0]->operator)->toBe('==');
        expect($conditions[1]->operator)->toBe('!=');
        expect($conditions[2]->operator)->toBe('>');
        expect($conditions[3]->operator)->toBe('>=');
        expect($conditions[4]->operator)->toBe('<');
        expect($conditions[5]->operator)->toBe('<=');
        expect($conditions[6]->operator)->toBe('===');
    });

    it('can add OR conditions', function () {
        $builder = Builder::make()
            ->where('field1', 'value1')
            ->orWhere('field2', 'value2');
        $conditions = $builder->getConditions();

        expect($conditions)->toEqual([
            new Where('field1', '==', 'value1'),
            new Where('field2', '==', 'value2', 'or'),
        ]);
    });

    it('can add NOT conditions', function () {
        $builder = Builder::make()
            ->where('field1', 'value1')
            ->whereNot('field2', 'value2');
        $conditions = $builder->getConditions();

        expect($conditions)->toEqual([
            new Where('field1', '==', 'value1'),
            new Where('field2', '==', 'value2', 'and', true),
        ]);
    });

    it('can add OR NOT conditions', function () {
        $builder = Builder::make()
            ->where('field1', 'value1')
            ->orWhereNot('field2', 'value2');
        $conditions = $builder->getConditions();

        expect($conditions)->toEqual([
            new Where('field1', '==', 'value1'),
            new Where('field2', '==', 'value2', 'or', true),
        ]);
    });

    it('can add NULL conditions', function () {
        $builder = Builder::make()
            ->where('field1', null)
            ->where('field1', '===', null)
            ->whereNull('field1')
            ->whereNotNull('field2')
            ->orWhereNull('field3')
            ->orWhereNotNull('field4');
        $conditions = $builder->getConditions();

        expect($conditions)->toEqual([
            new Where('field1', '==', null),
            new Where('field1', '===', null),
            new Where('field1', '==', null),
            new Where('field2', '==', null, 'and', true),
            new Where('field3', '==', null, 'or'),
            new Where('field4', '==', null, 'or', true),
        ]);
    });

    it('can add instanceof conditions', function () {
        $builder = Builder::make()
            ->whereInstanceOf('field1', 'stdClass')
            ->orWhereInstanceOf('field2', 'stdClass')
            ->whereNotInstanceOf('field3', 'stdClass')
            ->orWhereNotInstanceOf('field4', 'stdClass');
        $conditions = $builder->getConditions();

        expect($conditions)->toEqual([
            new Where('field1', 'instanceof', 'stdClass'),
            new Where('field2', 'instanceof', 'stdClass', 'or'),
            new Where('field3', 'instanceof', 'stdClass', 'and', true),
            new Where('field4', 'instanceof', 'stdClass', 'or', true),
        ]);
    });

    it('can add IN conditions', function () {
        $builder = Builder::make()
            ->whereIn('field1', ['value1', 'value2'])
            ->orWhereIn('field2', ['value3', 'value4'])
            ->whereNotIn('field3', ['value5', 'value6'])
            ->orWhereNotIn('field4', ['value7', 'value8']);
        $conditions = $builder->getConditions();

        expect($conditions)->toEqual([
            new Where('field1', 'in', ['value1', 'value2']),
            new Where('field2', 'in', ['value3', 'value4'], 'or'),
            new Where('field3', 'in', ['value5', 'value6'], 'and', true),
            new Where('field4', 'in', ['value7', 'value8'], 'or', true),
        ]);
    });

    it('can add BETWEEN conditions', function () {
        $range = [1, 3];
        $builder = Builder::make()
            ->whereBetween('field1', $range)
            ->orWhereBetween('field2', $range)
            ->whereNotBetween('field3', $range)
            ->orWhereNotBetween('field4', $range);
        $conditions = $builder->getConditions();

        expect($conditions)->toEqual([
            new Where('field1', 'between', $range),
            new Where('field2', 'between', $range, 'or'),
            new Where('field3', 'between', $range, 'and', true),
            new Where('field4', 'between', $range, 'or', true),
        ]);
    });
});

describe('Column Comparisons', function () {
    it('can add column comparisons', function () {
        $builder = Builder::make()
            ->whereColumn('field1', 'field2')
            ->whereColumn('field3', '!=', 'field4')
            ->whereColumn('field5', '>', 'field6')
            ->whereColumn('field7', '>=', 'field8')
            ->whereColumn('field9', '<', 'field10')
            ->whereColumn('field11', '<=', 'field12')
            ->whereColumnStrict('field13', 'field14');

        $conditions = $builder->getConditions();

        expect($conditions)->toHaveCount(7);
        expect($conditions[0]->operator)->toBe('==');
        expect($conditions[1]->operator)->toBe('!=');
        expect($conditions[2]->operator)->toBe('>');
        expect($conditions[3]->operator)->toBe('>=');
        expect($conditions[4]->operator)->toBe('<');
        expect($conditions[5]->operator)->toBe('<=');
        expect($conditions[6]->operator)->toBe('===');
    });

    it('can add IN comparisons', function () {
        $range = ['foo', 'bar'];
        $builder = Builder::make()
            ->whereColumnIn('field1', $range)
            ->whereColumnNotIn('field2', $range)
            ->orWhereColumnIn('field3', $range)
            ->orWhereColumnNotIn('field4', $range);

        $conditions = $builder->getConditions();

        expect($conditions)->toEqual([
            new WhereColumn('field1', 'in', $range),
            new WhereColumn('field2', 'in', $range, 'and', true),
            new WhereColumn('field3', 'in', $range, 'or'),
            new WhereColumn('field4', 'in', $range, 'or', true),
        ]);
    });

    it('can add BETWEEN comparisons', function () {
        $range = ['foo', 'bar'];
        $builder = Builder::make()
            ->whereColumnBetween('field1', $range)
            ->whereColumnNotBetween('field2', $range)
            ->orWhereColumnBetween('field3', $range)
            ->orWhereColumnNotBetween('field4', $range);

        $conditions = $builder->getConditions();

        expect($conditions)->toEqual([
            new WhereColumn('field1', 'between', $range),
            new WhereColumn('field2', 'between', $range, 'and', true),
            new WhereColumn('field3', 'between', $range, 'or'),
            new WhereColumn('field4', 'between', $range, 'or', true),
        ]);
    });
});


describe('Nested Conditions', function () {
    test('`where()` will accept a \Closure to add nested conditions', function () {
        $builder = Builder::make()->where(function ($builder) {
            $builder->where('field1', 'value1')
                ->where('field2', 'value2');
        });
        $conditions = $builder->getConditions();

        expect($conditions)->toEqual([
            new NestedCondition([
                new Where('field1', '==', 'value1'),
                new Where('field2', '==', 'value2'),
            ]),
        ]);
    });

    test('`where()` can accept the conjunction and negate parameters with a \Closure', function () {
        $builder = Builder::make()->where(function ($builder) {
            $builder->where('field1', 'value1')
                ->where('field2', 'value2');
        }, null, null, 'or', true);
        $conditions = $builder->getConditions();

        expect($conditions)->toEqual([
            new NestedCondition([
                new Where('field1', '==', 'value1'),
                new Where('field2', '==', 'value2'),
            ], 'or', true),
        ]);
    });

    it('can add nested conditions with `orWhere()`', function () {
        $builder = Builder::make()->orWhere(function ($builder) {
            $builder->where('field1', 'value1')
                ->where('field2', 'value2');
        });
        $conditions = $builder->getConditions();

        expect($conditions)->toEqual([
            new NestedCondition([
                new Where('field1', '==', 'value1'),
                new Where('field2', '==', 'value2'),
            ], 'or', false),
        ]);
    });

    it('can add nested conditions with `whereNot()`', function () {
        $builder = Builder::make()->whereNot(function ($builder) {
            $builder->where('field1', 'value1')
                ->where('field2', 'value2');
        });
        $conditions = $builder->getConditions();

        expect($conditions)->toEqual([
            new NestedCondition([
                new Where('field1', '==', 'value1'),
                new Where('field2', '==', 'value2'),
            ], 'and', true),
        ]);
    });

    it('can add nested conditions with `orWhereNot()`', function () {
        $builder = Builder::make()->orWhereNot(function ($builder) {
            $builder->where('field1', 'value1')
                ->where('field2', 'value2');
        });
        $conditions = $builder->getConditions();

        expect($conditions)->toEqual([
            new NestedCondition([
                new Where('field1', '==', 'value1'),
                new Where('field2', '==', 'value2'),
            ], 'or', true),
        ]);
    });
});