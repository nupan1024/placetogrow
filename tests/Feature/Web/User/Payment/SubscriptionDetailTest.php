<?php

use App\Contracts\PaymentService;
use App\Domain\Payments\Models\Payment;
use App\Support\Definitions\PaymentStatus;
use App\Support\Services\Payments\QueryPaymentResponse;

test('view subscription detail payment', function () {
    $payment = Payment::factory()->create();

    $mock = Mockery::mock(PaymentService::class);
    $mock->shouldReceive('getPaymentStatus')->andReturn(
        new QueryPaymentResponse(
            "",
            PaymentStatus::APPROVED->value
        )
    );

    $response = $this->get(route("payment.subscription.detail", $payment->id));

    $response->assertStatus(200);
});
