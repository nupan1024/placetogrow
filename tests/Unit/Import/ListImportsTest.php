<?php

use App\Domain\Imports\Actions\ListImport;

test('list imports', function () {
    $params = [
        'page' => 1,
        'filter' => ""
    ];
    expect(ListImport::execute($params))->toBeObject();
});

test('get imports with filter', function () {
    $params = [
        'page' => 1,
        'filter' => fake()->word()
    ];
    expect(ListImport::execute($params))->toBeObject();
});
