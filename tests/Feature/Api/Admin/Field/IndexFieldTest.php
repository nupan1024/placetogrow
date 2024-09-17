<?php

use App\Domain\Users\Models\User;
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\get;

test('list fields for admin', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);
    $response = get('/api/admin/fields');

    $response->assertStatus(200);
});

test('Not list fields because need token', function () {

    $response = get('/api/admin/fields');

    $response->assertStatus(302);
});
