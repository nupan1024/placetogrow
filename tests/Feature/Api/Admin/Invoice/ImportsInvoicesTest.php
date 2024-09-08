<?php

use App\Domain\Users\Models\User;
use Laravel\Sanctum\Sanctum;

test('list imports for admin', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);
    $response = $this->get(route('api.admin.invoices.imports'));

    $response->assertStatus(200);
});

test('Not list imports because need token', function () {

    $response = $this->get(route('api.admin.invoices.imports'));

    $response->assertStatus(302);
});
