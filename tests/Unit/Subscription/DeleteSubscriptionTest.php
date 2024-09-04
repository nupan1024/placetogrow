<?php

use App\Domain\Subscriptions\Actions\DeleteSubscription;
use App\Domain\Subscriptions\Models\Subscription;
use Illuminate\Support\Facades\Log;

test('Delete subscription', function () {
    $subscription = Subscription::factory()->create();

    expect(DeleteSubscription::execute([], $subscription))
        ->toBeTrue();
});

test('Does not receive subscription model', function () {
    $mockModel = Mockery::mock(Subscription::class);

    $mockModel->shouldReceive('delete')
        ->andThrow(new \Exception('Error deleting model'));

    Log::shouldReceive('channel')
        ->with('Subscriptions')
        ->once()
        ->andReturnSelf()
        ->shouldReceive('error')
        ->with('Error deleting subscription: Error deleting model')
        ->once();

    $result = DeleteSubscription::execute([], $mockModel);
    expect($result)->toBeFalse();
});
