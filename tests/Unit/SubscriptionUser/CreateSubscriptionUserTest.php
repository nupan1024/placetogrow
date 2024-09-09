<?php

use App\Domain\Payments\Models\Payment;
use App\Domain\Subscriptions\Models\Subscription;
use App\Domain\SubscriptionUser\Actions\CreateSubscriptionUser;
use App\Domain\Users\Models\User;
use App\Support\Definitions\PaymentStatus;

test('Create a subscription for a user', function () {
    $user = User::factory()->create();
    $subscription = Subscription::factory()->create();
    $payment = Payment::factory()->create();
    $params = [
        'user_id' => $user->id,
        'subscription_id' => $subscription->id,
        'status' => PaymentStatus::PENDING->value,
        'payment_id' => $payment->id
    ];

    expect(CreateSubscriptionUser::execute($params))->toBeTrue();
});

test('Fail to create a subscription for a user', function () {
    $user = User::factory()->create();
    $payment = Payment::factory()->create();
    $params = [
        'user_id' => $user->id,
        'status' => PaymentStatus::PENDING->value,
        'payment_id' => $payment->id
    ];

    expect(CreateSubscriptionUser::execute($params))->toBeFalse();
});
