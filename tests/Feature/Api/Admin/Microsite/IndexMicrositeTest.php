<?php

use App\Domain\Users\Models\User;
use Laravel\Sanctum\Sanctum;

test('list microsites for admin', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);
    $response = $this->get('/api/admin/microsites');

    $response->assertStatus(200);
});

test('Not list microsites because need token', function () {

    $response = $this->get('/api/admin/microsites');

    $response->assertStatus(302);
});
