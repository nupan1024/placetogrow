<?php

use App\Domain\Users\Models\User;
use Laravel\Sanctum\Sanctum;

test('list subscriptions for user', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);
    $response = $this->get(route('api.user.subscriptions', $user->id));

    $response->assertStatus(200);
});

test('Not found subscriptions by user id', function () {
    $response = $this->get(route('api.user.subscriptions', 1));

    $response->assertStatus(302);
});
