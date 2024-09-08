<?php

use App\Domain\Imports\Actions\UpdateImport;
use App\Domain\Imports\Models\Import;
use App\Support\Definitions\ImportStatus;

test('update import', function () {

    $import = Import::factory()->create();
    $params = [
        'status' => fake()->randomElement(ImportStatus::toArray()),
        'errors' => [],
    ];

    expect(UpdateImport::execute($params, $import))->toBeTrue();
});

test('generate exception import update', function () {
    $import = \Mockery::mock(Import::class);
    $params = [
        'status' => fake()->randomElement(ImportStatus::toArray()),
        'errors' => [],
    ];

    expect(UpdateImport::execute($params, $import))->toBeFalse();
});
