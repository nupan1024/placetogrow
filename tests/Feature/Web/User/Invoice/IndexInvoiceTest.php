<?php

use App\Domain\Users\Models\User;

test('validate invoices route by user', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get(route('user.invoices.list'));

    $response->assertStatus(200);
});
