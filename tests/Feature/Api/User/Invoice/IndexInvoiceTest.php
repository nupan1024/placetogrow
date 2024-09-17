<?php

use App\Domain\Users\Models\User;
use Laravel\Sanctum\Sanctum;

test('list invoices for user', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);
    $response = $this->get('api/user/' . $user->id . '/invoices');

    $response->assertStatus(200);
});

test('Not found invoices by user id', function () {

    $response = $this->get('api/user/1/invoices');

    $response->assertStatus(302);
});
