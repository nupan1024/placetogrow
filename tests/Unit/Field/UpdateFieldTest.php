<?php

use App\Domain\Fields\Actions\UpdateField;
use Database\Factories\FieldFactory;

test('update field', function () {
    $field = FieldFactory::new()->create();
    $params = [
        'name' => 'Field test',
        'type' => 'input',
        'label' => 'Field test',
        'attributes' => [],
    ];

    expect(UpdateField::execute($params, $field))->toBeTrue();
});

test('generate exception field update', function () {
    $field = FieldFactory::new()->create();

    expect(UpdateField::execute([], $field))->toBeFalse();
});
