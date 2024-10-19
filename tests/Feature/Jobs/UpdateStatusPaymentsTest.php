<?php

use App\Domain\Payments\Models\Payment;
use App\Jobs\ProcessPayments;
use App\Support\Definitions\PaymentStatus;
use App\Support\Services\Payments\Gateways\PlaceToPayService;
use Dnetix\Redirection\Message\RedirectInformation;
use Dnetix\Redirection\PlacetoPay;

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

    $placeToPayMock = $this->mock(PlacetoPay::class);
    $placeToPayMock
        ->shouldReceive('query')->andReturn(
            new RedirectInformation(
                json_decode(file_get_contents('./tests/Stubs/sessionResponse.json'), true)
            )
        );

    $this->mock(PlaceToPayService::class)
        ->shouldReceive('init')->andReturn($placeToPayMock);

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
