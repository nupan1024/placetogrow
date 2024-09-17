<?php

use App\Domain\Microsites\Models\Microsite;
use App\Domain\Users\Models\User;
use Laravel\Sanctum\Sanctum;

test('list subscriptions for microsites for admin', function () {
    $user = User::factory()->create();
    $microsite = Microsite::factory()->create();
    Sanctum::actingAs($user);
    $response = $this->get(route('api.admin.microsite.subscriptions', $microsite->id));

    $response->assertStatus(200);
});

test('Not list subscriptions for because need token', function () {
    $microsite = Microsite::factory()->create();
    $response = $this->get(route('api.admin.microsite.subscriptions', $microsite->id));

    $response->assertStatus(302);
});
