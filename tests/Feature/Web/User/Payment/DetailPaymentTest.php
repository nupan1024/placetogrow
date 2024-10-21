<?php

use App\Domain\Payments\Models\Payment;

test('view detail payment', function () {

    $payment = Payment::factory()->create();
    $response = $this->get(route("payment.detail", $payment->id));

    $response->assertStatus(200);
    $response->assertSee($payment->value);
    $response->assertSee($payment->reference);
    $response->assertSee($payment->status);
});
