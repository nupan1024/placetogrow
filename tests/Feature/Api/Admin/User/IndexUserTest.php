<?php

use App\Domain\Users\Models\User;
use Laravel\Sanctum\Sanctum;

test('list users for admin', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);
    $response = $this->get('/api/admin/users');

    $response->assertStatus(200);
});

test('Not list users because need token', function () {

    $response = $this->get('/api/admin/users');

    $response->assertStatus(302);
});
