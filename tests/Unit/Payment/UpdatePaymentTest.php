<?php

use App\Domain\Payments\Actions\UpdatePayment;
use App\Domain\Payments\Models\Payment;
use App\Support\Definitions\StatusInvoices;

test('update payment', function () {
    $payment = Payment::factory()->create();
    $params = [
        'payment' => $payment,
        'status' => StatusInvoices::PAID->value,
        'url' => fake()->url(),
        'request_id' => '12345',
    ];
    expect(UpdatePayment::execute($params))->toBeTrue();
});
