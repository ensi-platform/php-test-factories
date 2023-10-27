<?php

use Ensi\TestFactories\Tests\Stubs\TestArrayFactory;
use Ensi\TestFactories\Tests\Stubs\TestObjectDTO;
use Ensi\TestFactories\Tests\Stubs\TestObjectFactory;
use Illuminate\Support\Collection;

test('testObjectFactory can create object', function () {
    $id = 5;
    $result = TestObjectFactory::new()->make(['id' => $id]);

    expect($result)->toBeInstanceOf(TestObjectDTO::class);
    expect($result->fields['id'])->toEqual($id);
});

test('testArrayFactory can create arrays', function () {
    $id = 5;
    $result = TestArrayFactory::new()->make(['id' => $id]);

    expect($result)->toBeArray();
    expect($result['id'])->toEqual($id);
});

test('makeSeveral works', function () {
    $id = 5;
    $count = 3;
    $result = TestObjectFactory::new()->makeSeveral($count, ['id' => $id]);

    expect($result)->toBeInstanceOf(Collection::class);
    expect($result)->toHaveCount($count);
    $result->each(fn ($x) => expect($x->fields['id'])->toEqual($id));
});

test('makeSeveralExtras works', function () {
    $extras = [['id' => 10], ['id' => 11, 'user_id' => 20]];
    $result = TestObjectFactory::new()->makeSeveralExtras(...$extras);

    expect($result)->toBeInstanceOf(Collection::class);
    expect($result)->toHaveCount(count($extras));
    $result->each(function ($obj) use ($extras) {
        static $i = 0;
        expect($obj->fields)->toMatchArray($extras[$i++]);
    });
});

test('whenNotNull excludes null field', function () {
    $result = TestArrayFactory::new()->make();

    expect($result)->not->toHaveKey('id');
});