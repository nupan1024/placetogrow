<?php

use App\Domain\Microsites\Actions\ListMicrositesForAdmin;

test('get microsites', function () {
    $params = [
        'page' => 1,
        'filter' => ""
    ];
    expect(ListMicrositesForAdmin::execute($params))->toBeObject();
});

test('get microsites with filter', function () {
    $params = [
        'page' => 1,
        'filter' => "Test"
    ];
    expect(ListMicrositesForAdmin::execute($params))->toBeObject();
});
