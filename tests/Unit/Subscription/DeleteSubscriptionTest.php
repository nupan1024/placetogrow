<?php

use App\Domain\Subscriptions\Actions\DeleteSubscription;
use App\Domain\Subscriptions\Models\Subscription;

test('Delete subscription', function () {
    $subscription = Subscription::factory()->create();

    expect(DeleteSubscription::execute([], $subscription))
        ->toBeTrue();
});

test('Does not receive subscription model', function () {
    $mockModel = Mockery::mock(Subscription::class);

    $mockModel->shouldReceive('delete')
        ->andReturn(false);

    $result = DeleteSubscription::execute([], $mockModel);
    expect($result)->toBeFalse();
});
