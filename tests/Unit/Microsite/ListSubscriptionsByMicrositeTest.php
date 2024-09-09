<?php

use App\Domain\Microsites\Actions\ListSubscriptionsByMicrosite;
use App\Domain\Microsites\Models\Microsite;

test('get subscriptions by microsite', function () {
    $params = [
        'page' => 1,
        'filter' => ""
    ];
    $microsite = Microsite::factory()->create();
    expect(ListSubscriptionsByMicrosite::execute($params, $microsite))->toBeObject();
});

test('get subscriptions by microsite with filter', function () {
    $params = [
        'page' => 1,
        'filter' => "Test"
    ];
    $microsite = Microsite::factory()->create();
    expect(ListSubscriptionsByMicrosite::execute($params, $microsite))->toBeObject();
});
