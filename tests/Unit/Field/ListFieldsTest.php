<?php

use App\Domain\Fields\Actions\ListFields;

test('list fields', function () {
    $params = [
        'page' => 1,
        'filter' => ""
    ];
    expect(ListFields::execute($params))->toBeObject();
});

test('get fields with filter', function () {
    $params = [
        'page' => 1,
        'filter' => "Test"
    ];
    expect(ListFields::execute($params))->toBeObject();
});
