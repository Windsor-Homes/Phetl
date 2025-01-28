<?php

use Windsor\Phetl\Utils\Conditions\Builder;
use Windsor\Phetl\Utils\Conditions\NestedCondition;



it('can add a simple condition', function () {
    $builder = Builder::make()->where('field', '=', 'value');
    $conditions = $builder->getConditions();

    expect($conditions)->toHaveCount(1);
    expect($conditions[0]->field)->toBe('field');
    expect($conditions[0]->operator)->toBe('=');
    expect($conditions[0]->value)->toBe('value');
});

it('can pass flexible parameters', function () {
    $builder = Builder::make()
        ->where('field', 'value')
        ->where(function ($builder) {
            $builder->where('field2', 'value2');
        });
    $conditions = $builder->getConditions();

    expect($conditions)->toHaveCount(2);
    expect($conditions[0]->field)->toBe('field');
    expect($conditions[0]->operator)->toBe('=');
    expect($conditions[0]->value)->toBe('value');

    expect($conditions[1])->toBeInstanceOf(NestedCondition::class);
    $nested = $conditions[1];
    expect($nested->conditions)->toHaveCount(1);
    expect($nested->conditions[0]->field)->toBe('field2');
    expect($nested->conditions[0]->operator)->toBe('=');
    expect($nested->conditions[0]->value)->toBe('value2');
});

it('can add multiple conditions', function () {
    $builder = Builder::make()->where('field1', '=', 'value1');
    $builder->where('field2', '>', 'value2');
    $conditions = $builder->getConditions();

    expect($conditions)->toHaveCount(2);
    expect($conditions[0]->field)->toBe('field1');
    expect($conditions[0]->operator)->toBe('=');
    expect($conditions[0]->value)->toBe('value1');
    expect($conditions[1]->field)->toBe('field2');
    expect($conditions[1]->operator)->toBe('>');
    expect($conditions[1]->value)->toBe('value2');
});

it('can add nested conditions', function () {
    $builder = Builder::make()
        ->where('field1', '=', 'value1')
        ->where(function ($builder) {
            $builder->where('field2', '>', 'value2');
            $builder->where('field3', '<', 'value3');
        });
    $conditions = $builder->getConditions();

    expect($conditions)->toHaveCount(2);
    expect($conditions[1])->toBeInstanceOf(NestedCondition::class);

    $nested = $conditions[1];
    expect($nested->conditions)->toHaveCount(2);
    expect($nested->conditions[0]->field)->toBe('field2');
    expect($nested->conditions[0]->operator)->toBe('>');
    expect($nested->conditions[0]->value)->toBe('value2');
    expect($nested->conditions[1]->field)->toBe('field3');
    expect($nested->conditions[1]->operator)->toBe('<');
    expect($nested->conditions[1]->value)->toBe('value3');
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
    expect($conditions[0]->operator)->toBe('=');
    expect($conditions[1]->operator)->toBe('!=');
    expect($conditions[2]->operator)->toBe('>');
    expect($conditions[3]->operator)->toBe('>=');
    expect($conditions[4]->operator)->toBe('<');
    expect($conditions[5]->operator)->toBe('<=');
    expect($conditions[6]->operator)->toBe('===');
});

it('can add IN comparisons', function () {
    $builder = Builder::make()->whereIn('field1', ['value1', 'value2']);
    $conditions = $builder->getConditions();

    expect($conditions)->toHaveCount(1);
    expect($conditions[0]->operator)->toBe('in');
});

it('can add Between comparisons', function () {
    $builder = Builder::make()->whereBetween('field1', ['value1', 'value2']);
    $conditions = $builder->getConditions();

    expect($conditions)->toHaveCount(1);
    expect($conditions[0]->operator)->toBe('between');
});

it('can add instanceof conditions', function () {
    $builder = Builder::make()
        ->whereInstanceOf('field1', 'stdClass')
        ->whereNotInstanceOf('field2', 'stdClass');
    $conditions = $builder->getConditions();

    expect($conditions)->toHaveCount(2);
    expect($conditions[0]->operator)->toBe('instanceof');
});

it('can add NULL conditions', function () {
    $builder = Builder::make()
        ->whereNull('field1')
        ->whereNotNull('field2');
    $conditions = $builder->getConditions();

    expect($conditions)->toHaveCount(2);
    expect($conditions[0]->operator)->toBe('null');
});

it('can evaluate equals conditions', function () {
    $builder = Builder::make()->where('field', 'value');
    $data = ['field' => 'value'];

    $result = $builder->evaluate($data);
    expect($result)->toBeTrue();
});

it('can evaluate not equals conditions', function () {
    $builder = Builder::make()->whereNot('field', 'value');
    $data = ['field' => 'other'];

    $result = $builder->evaluate($data);
    expect($result)->toBeTrue();
});

it('can evaluate greater than conditions', function () {
    $builder = Builder::make()
        ->where('field', '>', 3)
        ->whereNot('field2', '>', 3);
    $data = ['field' => 4, 'field2' => 2];

    $result = $builder->evaluate($data);
    expect($result)->toBeTrue();
});

it('can evaluate greater than or equal conditions', function () {
    $builder = Builder::make()
        ->where('field', '>=', 3)
        ->whereNot('field2', '>=', 3);
    $data = ['field' => 3, 'field2' => 2];

    $result = $builder->evaluate($data);
    expect($result)->toBeTrue();
});

it('can evaluate less than conditions', function () {
    $builder = Builder::make()
        ->where('field', '<', 3)
        ->whereNot('field2', '<', 3);
    $data = ['field' => 2, 'field2' => 4];

    $result = $builder->evaluate($data);
    expect($result)->toBeTrue();
});

it('can evaluate less than or equal conditions', function () {
    $builder = Builder::make()
        ->where('field', '<=', 3)
        ->whereNot('field2', '<=', 3);
    $data = [
        'field' => 3,
        'field2' => 4,
    ];

    $result = $builder->evaluate($data);
    expect($result)->toBeTrue();
});

it('can evaluate strict conditions', function () {
    $builder = Builder::make()
        ->whereStrict('field', 3)
        ->whereNotStrict('field2', 2);
    $data = [
        'field' => 3,
        'field2' => '2',
    ];

    $result = $builder->evaluate($data);
    expect($result)->toBeTrue();
});

it('can evaluate IN conditions', function () {
    $builder = Builder::make()
        ->whereIn('field', [1, 2, 3])
        ->whereNotIn('field2', [4, 5, 6]);
    $data = [
        'field' => 2,
        'field2' => 7,
    ];

    $result = $builder->evaluate($data);
    expect($result)->toBeTrue();
});

it('can evaluate BETWEEN conditions', function () {
    $builder = Builder::make()
        ->whereBetween('field', [1, 3])
        ->whereNotBetween('field2', [4, 6]);
    $data = [
        'field' => 2,
        'field2' => 7,
    ];

    $result = $builder->evaluate($data);
    expect($result)->toBeTrue();
});

it('can evaluate NULL conditions', function () {
    $builder = Builder::make()
        ->whereNull('field')
        ->whereNotNull('field2');
    $data = [
        'field' => null,
        'field2' => 'value',
    ];

    $result = $builder->evaluate($data);
    expect($result)->toBeTrue();
});

it('can evaluate instanceof conditions', function () {
    $builder = Builder::make()
        ->whereInstanceOf('field', \stdClass::class)
        ->whereNotInstanceOf('field2', \stdClass::class);
    $data = [
        'field' => new \stdClass(),
        'field2' => 'value',
    ];

    $result = $builder->evaluate($data);
    expect($result)->toBeTrue();
});

it('can evaluate column conditions', function () {
    $builder = Builder::make()
        ->whereColumn('field1', 'field2')
        ->whereColumnNot('field3', 'field4');
    $data = [
        'field1' => 'foo',
        'field2' => 'foo',
        'field3' => 'bar',
        'field4' => 'baz',
    ];

    $result = $builder->evaluate($data);
    expect($result)->toBeTrue();
});

it('can evaluate column IN conditions', function () {
    $builder = Builder::make()
        ->whereColumnIn('field1', ['field2', 'field3'])
        ->whereColumnNotIn('field3', ['field1', 'field4']);
    $data = [
        'field1' => 'foo',
        'field2' => 'foo',
        'field3' => 'bar',
        'field4' => 'baz',
    ];

    $result = $builder->evaluate($data);
    expect($result)->toBeTrue();
});

it('can evaluate column BETWEEN conditions', function () {
    $builder = Builder::make()
        ->whereColumnBetween('field1', ['field3', 'field4'])
        ->whereColumnNotBetween('field2', ['field3', 'field4']);
    $data = [
        'field1' => 2,
        'field2' => 4,
        'field3' => 1,
        'field4' => 3,
    ];

    $result = $builder->evaluate($data);
    expect($result)->toBeTrue();
});

it('returns false when no conditions are met', function () {
    $builder = Builder::make()->where('field', '=', 'value');
    $data = [['field' => 'other']];

    $result = $builder->evaluate($data);

    expect($result)->toBeFalse();
});