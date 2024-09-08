<?php

use App\Domain\Currencies\Models\Currency;
use App\Domain\Microsites\Models\Microsite;
use App\Domain\Subscriptions\Actions\CreateSubscription;
use App\Domain\Subscriptions\Models\Subscription;
use App\Support\Definitions\BillingFrequency;

test('update subscription', function () {
    $subscription = Subscription::factory()->create();
    $microsite = Microsite::factory()->create();
    $currency = Currency::factory()->create();
    $params = [
        'microsite_id' => $microsite->id,
        'currency_id' => $currency->id,
        'amount' => '6000',
        'description' => fake()->paragraph(),
        'name' => fake()->name(),
        'status' => fake()->boolean(),
        'billing_frequency' => BillingFrequency::MONTH->value,
        'time_expire' => fake()->dateTimeBetween('now', '+1 year')->format('Y-m-d'),
    ];

    expect(CreateSubscription::execute($params, $subscription))->toBeTrue();
});

test('generate exception subscription update', function () {
    $subscription = Subscription::factory()->create();
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

    expect(CreateSubscription::execute($params, $subscription))->toBeFalse();
});
