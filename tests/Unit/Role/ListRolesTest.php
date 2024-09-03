<?php

use App\Domain\Roles\Actions\ListRoles;

test('list roles', function () {
    $params = [
        'page' => 1,
        'filter' => ""
    ];
    expect(ListRoles::execute($params))->toBeObject();
});

test('get roles with filter', function () {
    $params = [
        'page' => 1,
        'filter' => "Test"
    ];
    expect(ListRoles::execute($params))->toBeObject();
});
