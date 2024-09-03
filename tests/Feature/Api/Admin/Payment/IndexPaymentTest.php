<?php

use App\Domain\Users\Models\User;
use Laravel\Sanctum\Sanctum;

test('list payments for admin', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);
    $response = $this->get('/api/admin/payments');

    $response->assertStatus(200);
});

test('Not list payments because need token', function () {

    $response = $this->get('/api/admin/payments');

    $response->assertStatus(302);
});
