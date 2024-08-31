<?php

use App\Domain\Users\Models\User;
use Illuminate\Support\Facades\Hash;

test('generate token successfully', function () {

    $user = User::factory()->create([
        'password' => Hash::make('password123'),
    ]);

    $response = $this->postJson('/api/getToken', [
        'email' => $user->email,
        'password' => 'password123',
    ]);

    $response->assertStatus(200);

    $response->assertJsonStructure(['data' => ['token']]);
});

test('generate token fails with incorrect credentials', function () {
    $user = User::factory()->create();

    $response = $this->postJson('/api/getToken', [
        'email' => $user->email,
        'password' => 'wrongpassword',
    ]);

    $response->assertStatus(422);

    $response->assertJson([
        'message' => 'Validation failed',
        'errors' => [
            'email' => ['The provided credentials are incorrect.'],
        ],
    ]);
});
