<?php

use App\Domain\Users\Models\User;
use Illuminate\Support\Facades\Hash;

$url = '/profile';

test('password can be updated', function () use ($url) {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->from($url)
        ->put('/password', [
            'current_password' => 'password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect($url);

    $this->assertTrue(Hash::check('new-password', $user->refresh()->password));
});

test('correct password must be provided to update password', function () use ($url) {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->from($url)
        ->put('/password', [
            'current_password' => 'wrong-password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

    $response
        ->assertSessionHasErrors('current_password')
        ->assertRedirect($url);
});
