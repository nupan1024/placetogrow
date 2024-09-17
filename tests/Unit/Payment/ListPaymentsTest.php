<?php

use App\Domain\Payments\Actions\ListPayments;

test('list payments', function () {
    $params = [
        'page' => 1,
        'filter' => ""
    ];
    expect(ListPayments::execute($params))->toBeObject();
});

test('get payments with filter', function () {
    $params = [
        'page' => 1,
        'filter' => "Test"
    ];
    expect(ListPayments::execute($params))->toBeObject();
});
