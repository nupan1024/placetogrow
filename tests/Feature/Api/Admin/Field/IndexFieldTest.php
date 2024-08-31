<?php

use App\Domain\Users\Models\User;
use Laravel\Sanctum\Sanctum;

test('list fields for admin', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);
    $response = $this->get('/api/admin/fields');

    $response->assertStatus(200);
});

test('Not list fields because need token', function () {

    $response = $this->get('/api/admin/fields');

    $response->assertStatus(302);
});
