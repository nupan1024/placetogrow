<?php

use App\Domain\Payments\Models\Payment;
use App\Jobs\ProcessPayments;
use App\Support\Definitions\PaymentStatus;
use App\Support\Services\Payments\Gateways\PlaceToPayService;
use App\Support\Services\Payments\QueryPaymentResponse;

test('job update payments', function () {
    $payment = Payment::factory()->create([
        'status' => PaymentStatus::PENDING->value,
        'request_id' => fake()->randomNumber(6, true),
    ]);

    config()->set('gateways.placetopay', [
        'login' => 'login',
        'secret_key' => 'secret_key',
        'base_url' => fake()->url(),
    ]);

    $placeToPayMock = $this->mock(PlaceToPayService::class);
    $placeToPayMock->shouldReceive('getPaymentStatus')
        ->andReturn(new QueryPaymentResponse(
            'OK',
            PaymentStatus::APPROVED->value
        ));

    $job = new ProcessPayments();
    $job->handle();

    $this->assertDatabaseHas('payments', [
        'id' => $payment->id,
        'status' => PaymentStatus::APPROVED->value,
    ]);
});

test('job update payment to rejected when request_id is 0', function () {
    Payment::factory()->create([
        'status' => PaymentStatus::PENDING->value,
        'request_id' => 0,
    ]);
    $job = new ProcessPayments();
    $job->handle();

    $this->assertDatabaseHas('payments', [
        'status' => PaymentStatus::REJECTED->value,
    ]);
});
