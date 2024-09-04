<?php

use App\Domain\Currencies\Models\Currency;
use App\Domain\Microsites\Models\Microsite;
use App\Domain\Subscriptions\Actions\CreateSubscription;
use App\Support\Definitions\BillingFrequency;

test('create subscription', function () {
    $microsite = Microsite::factory()->create();
    $currency = Currency::factory()->create();
    $params = [
        'microsite_id' => $microsite->id,
        'currency_id' => $currency->id,
        'amount' => fake()->numberBetween(1000, 10000),
        'description' => fake()->paragraph(),
        'name' => fake()->name(),
        'status' => fake()->boolean(),
        'billing_frequency' => BillingFrequency::MONTH->value,
        'time_expire' => fake()->dateTimeBetween('now', '+1 year')->format('Y-m-d'),
    ];

    expect(CreateSubscription::execute($params))->toBeTrue();
});

test('generate exception subscription creation', function () {
    $currency = Currency::factory()->create();
    $params = [
        'currency_id' => $currency->id,
        'amount' => '6000',
        'description' => fake()->paragraph(),
        'name' => fake()->name(),
        'status' => fake()->boolean(),
        'billing_frequency' => BillingFrequency::MONTH->value,
        'time_expire' => fake()->dateTimeBetween('now', '+1 year')->format('Y-m-d'),
    ];

    expect(CreateSubscription::execute($params))->toBeFalse();
});
