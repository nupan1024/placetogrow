<?php

use App\Domain\Users\Models\Role;
use App\Support\Definitions\Roles;

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new users can register', function () {
    Role::factory()
        ->create([
            'id' => Roles::GUEST->value,
            'name' => Roles::GUEST->name,
        ]);

    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));
});
