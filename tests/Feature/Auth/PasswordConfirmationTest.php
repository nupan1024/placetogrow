<?php

use App\Domain\Users\Models\User;

 $url = '/confirm-password';

test('confirm password screen can be rendered', function () use($url) {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get($url);

    $response->assertStatus(200);
});

test('password can be confirmed', function () use($url) {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post($url, [
        'password' => 'password',
    ]);

    $response->assertRedirect();
    $response->assertSessionHasNoErrors();
});

test('password is not confirmed with invalid password', function () use($url) {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post($url, [
        'password' => 'wrong-password',
    ]);

    $response->assertSessionHasErrors();
});
