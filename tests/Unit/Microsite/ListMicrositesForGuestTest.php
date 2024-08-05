<?php

use App\Domain\Microsites\Actions\ListMicrositesForGuest;

test('get microsites', function () {
    $params = [
        'page' => 1,
        'filter' => ""
    ];
    expect(ListMicrositesForGuest::execute($params))->toBeObject();
});

test('get microsites with filter', function () {
    $params = [
        'page' => 1,
        'filter' => "Test"
    ];
    expect(ListMicrositesForGuest::execute($params))->toBeObject();
});
