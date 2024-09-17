<?php

use App\Domain\Subscriptions\Actions\ListSubscription;

test('list subscriptions', function () {
    $params = [
        'page' => 1,
        'filter' => ""
    ];
    expect(ListSubscription::execute($params))->toBeObject();
});

test('get subscriptions with filter', function () {
    $params = [
        'page' => 1,
        'filter' => "Test"
    ];
    expect(ListSubscription::execute($params))->toBeObject();
});
