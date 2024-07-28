<?php

use App\Domain\Users\Actions\ListUsers;

test('list users', function () {
    $params = [
        'page' => 1,
        'filter' => ""
    ];
    expect(ListUsers::execute($params))->toBeObject();
});

test('get users with filter', function () {
    $params = [
        'page' => 1,
        'filter' => "Test"
    ];
    expect(ListUsers::execute($params))->toBeObject();
});
