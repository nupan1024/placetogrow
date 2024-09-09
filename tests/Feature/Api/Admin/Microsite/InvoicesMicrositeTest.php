<?php

use App\Domain\Microsites\Models\Microsite;
use App\Domain\Users\Models\User;
use Laravel\Sanctum\Sanctum;

test('list invoices for microsites for admin', function () {
    $user = User::factory()->create();
    $microsite = Microsite::factory()->create();
    Sanctum::actingAs($user);
    $response = $this->get(route('api.admin.microsite.invoices', $microsite->id));

    $response->assertStatus(200);
});

test('Not list invoices for because need token', function () {
    $microsite = Microsite::factory()->create();
    $response = $this->get(route('api.admin.microsite.invoices', $microsite->id));

    $response->assertStatus(302);
});
