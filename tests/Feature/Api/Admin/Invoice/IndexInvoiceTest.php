<?php

use App\Domain\Users\Models\User;
use Laravel\Sanctum\Sanctum;

test('list invoices for admin', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);
    $response = $this->get('api/admin/invoices');

    $response->assertStatus(200);
});

test('Not list invoices because need token', function () {

    $response = $this->get('api/admin/invoices');

    $response->assertStatus(302);
});
