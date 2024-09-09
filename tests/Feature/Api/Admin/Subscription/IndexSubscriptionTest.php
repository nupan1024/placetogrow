<?php

use App\Domain\Users\Models\User;
use Laravel\Sanctum\Sanctum;

test('list subscriptions for admin', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);
    $response = $this->get(route('api.admin.subscriptions'));

    $response->assertStatus(200);
});

test('Not list subscriptions because need token', function () {

    $response = $this->get(route('api.admin.subscriptions'));

    $response->assertStatus(302);
});
