<?php

use App\Domain\Users\Models\User;

$url = '/login';

test('login screen can be rendered', function () use ($url) {
    $response = $this->get($url);

    $response->assertStatus(200);
});

test('users can authenticate using the login screen', function () use ($url) {
    $user = User::factory()->create();

    $response = $this->post($url, [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));
});

test('users can not authenticate with invalid password', function () use ($url) {
    $user = User::factory()->create();

    $this->post($url, [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $this->assertGuest();
});

test('users can logout', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/logout');

    $this->assertGuest();
    $response->assertRedirect('/');
});
