<?php

use App\Domain\Users\Models\Role;
use App\Domain\Users\Models\User;
use App\Support\Definitions\Roles;
use Laravel\Sanctum\Sanctum;

test('validate dashboard route with any role', function () {
    Role::factory()->create([
        'id' => Roles::SUPER_ADMIN->value,
        'name' => Roles::SUPER_ADMIN->name,
        'guard_name' => 'web',
    ]);

    $user = User::factory()->create([
        'role_id' => Roles::SUPER_ADMIN->value,
    ]);
    Sanctum::actingAs($user);

    $response = $this->get('/dashboard');

    $response->assertStatus(200);
});


test('validate dashboard route with guest role', function () {
    Role::factory()->create([
        'id' => Roles::GUEST->value,
        'name' => Roles::GUEST->name,
        'guard_name' => 'web',
    ]);

    $user = User::factory()->create([
        'role_id' => Roles::GUEST->value,
    ]);
    Sanctum::actingAs($user);

    $response = $this->get('/dashboard');

    $response->assertStatus(302);
});
