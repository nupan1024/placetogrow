<?php

use App\Domain\SubscriptionUser\Actions\ListSubscriptionUser;
use App\Domain\Users\Models\User;

test('list subscriptions by user', function () {
    $user = User::factory()->create();
    $params = [
        'page' => 1,
        'filter' => "",
        'user_id' => $user->id
    ];
    expect(ListSubscriptionUser::execute($params))->toBeObject();
});

test('get subscriptions by user with filter', function () {
    $user = User::factory()->create();

    $params = [
        'page' => 1,
        'filter' => "Test",
        'user_id' => $user->id
    ];
    expect(ListSubscriptionUser::execute($params))->toBeObject();
});
