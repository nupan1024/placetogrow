<?php

use App\Domain\Fields\Actions\CreateField;

test('create field', function () {
    $params = [
        'name' => 'Field test',
        'type' => 'input',
        'label' => 'Field test',
        'attributes' => [],
    ];

    expect(CreateField::execute($params))->toBeTrue();
});

test('generate exception field creation', function () {
    $params = [
        'name' => 'Field test',
        'attributes' => [],
    ];

    expect(CreateField::execute($params))->toBeFalse();
});
