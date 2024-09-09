<?php

use App\Domain\Invoices\Models\Invoice;
use App\Domain\Payments\Actions\UpdatePayment;
use App\Domain\Payments\Models\Payment;
use App\Domain\Subscriptions\Models\Subscription;
use App\Support\Definitions\StatusInvoices;

test('update payment', function () {
    $payment = Payment::factory()->create();
    $params = [
        'status' => StatusInvoices::PAID->value,
        'url' => fake()->url(),
        'request_id' => fake()->numberBetween(100000, 999999),
    ];
    expect(UpdatePayment::execute($params, $payment))->toBeTrue();
});

test('update payment with subscription', function () {
    $subscription = Subscription::factory()->create();
    $payment = Payment::factory()->create(
        ['subscription_id' => $subscription->id]
    );

    $params = [
        'status' => StatusInvoices::PAID->value,
        'url' => fake()->url(),
        'request_id' => fake()->numberBetween(100000, 999999),
    ];
    expect(UpdatePayment::execute($params, $payment))->toBeTrue();
});

test('update payment with invoice', function () {
    $invoice = Invoice::factory()->create();
    $payment = Payment::factory()->create([
        'invoice_id' => $invoice->id,
    ]);
    $params = [
        'status' => StatusInvoices::PAID->value,
        'url' => fake()->url(),
        'request_id' => fake()->numberBetween(100000, 999999),
        'invoice_id' => $invoice->id,
    ];
    expect(UpdatePayment::execute($params, $payment))->toBeTrue();
});
