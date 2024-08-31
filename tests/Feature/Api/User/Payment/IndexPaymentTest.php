<?php

use App\Domain\Users\Models\User;
use Laravel\Sanctum\Sanctum;

test('list payments for user', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);
    $response = $this->get('/api/user/' . $user->id . '/payments');

    $response->assertStatus(200);
});

test('Not found payments by user id', function () {

    $response = $this->get('/api/user/1/payments/');

    $response->assertStatus(302);
});
