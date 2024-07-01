<?php

use App\Domain\Users\Models\User;

$url = '/profile';
$name = 'Test User';

test('profile page is displayed', function () use($url) {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->get($url);

    $response->assertOk();
});

test('profile information can be updated', function () use($url, $name) {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->patch($url, [
            'name' => $name,
            'email' => 'test@example.com',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect($url);

    $user->refresh();

    $this->assertSame($name, $user->name);
    $this->assertSame('test@example.com', $user->email);
    $this->assertNull($user->email_verified_at);
});

test('email verification status is unchanged when the email address is unchanged', function () use($url, $name) {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->patch($url, [
            'name' => $name,
            'email' => $user->email,
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect($url);

    $this->assertNotNull($user->refresh()->email_verified_at);
});

test('user can delete their account', function () use($url) {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->delete($url, [
            'password' => 'password',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/');

    $this->assertGuest();
    $this->assertNull($user->fresh());
});

test('correct password must be provided to delete account', function () use($url) {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->from($url)
        ->delete($url, [
            'password' => 'wrong-password',
        ]);

    $response
        ->assertSessionHasErrors('password')
        ->assertRedirect($url);

    $this->assertNotNull($user->fresh());
});
