<?php

use App\Domain\Currencies\Models\Currency;
use App\Domain\Microsites\Models\Microsite;
use App\Domain\Microsites\Models\MicrositeType;
use App\Domain\Payments\Models\Payment;
use App\Domain\Subscriptions\Models\Subscription;
use App\Support\Definitions\DocumentsTypes;
use App\Support\Definitions\MicrositesTypes;
use App\Support\Definitions\PaymentGateway;

test('view subscription detail payment', function () {
    $typeMicrosite = MicrositeType::factory()->create([
        'name' => MicrositesTypes::DONATIONS->name,
    ]);

    $subscription = Subscription::factory()->create();

    $microsite = Microsite::factory()->create([
        'microsites_type_id' => $typeMicrosite->id,
    ]);

    $currency = Currency::factory()->create([
        'name' => 'COP'
    ]);

    $data = [
        'microsite_id' => $microsite->id,
        'name' => fake()->name(),
        'currency' => $currency->name,
        'email' => fake()->email(),
        'type_document' => DocumentsTypes::CC->name,
        'num_document' => fake()->numberBetween(100000, 999999),
        'value' => fake()->numberBetween(100000, 999999),
        'subscription_id' => $subscription->id,
        'fields' => [],
        'gateway' => strtolower(PaymentGateway::PLACETOPAY->name),
    ];

    $this->post(route("payment.create"), $data);
    $payment = Payment::first();
    $response = $this->get(route("payment.subscription.detail", $payment->id));

    $response->assertStatus(200);
});