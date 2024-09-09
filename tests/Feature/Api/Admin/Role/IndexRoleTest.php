<?php

use App\Domain\Users\Models\User;
use Laravel\Sanctum\Sanctum;

test('list roles for admin', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);
    $response = $this->get('/api/admin/roles');

    $response->assertStatus(200);
});

test('Not list roles because need token', function () {

    $response = $this->get('/api/admin/roles');

    $response->assertStatus(302);
});
