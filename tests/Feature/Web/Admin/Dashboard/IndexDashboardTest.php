<?php

use App\Domain\Users\Models\User;
use App\Support\Definitions\Permissions;
use App\Support\Definitions\Roles;
use Database\Factories\PermissionFactory;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Models\Role;

test('validate dashboard route with any role', function () {
    PermissionFactory::new()->createMany(Permissions::toArray());

    $role = new Role();
    $role->name = Roles::SUPER_ADMIN->name;
    $role->syncPermissions([]);
    $role->save();

    $user = User::factory()->create([
        'role_id' => $role->id,
    ]);
    $user->assignRole(Role::findById($role->id));
    Sanctum::actingAs($user);

    $response = $this->get('/dashboard');

    $response->assertStatus(200);
});


test('validate dashboard route with guest role', function () {
    PermissionFactory::new()->createMany(Permissions::toArray());

    $role = new Role();
    $role->name = ucwords(strtolower(Roles::GUEST->name));
    $role->syncPermissions([]);
    $role->save();

    $user = User::factory()->create([
        'role_id' => $role->id,
    ]);
    $user->assignRole(Role::findById($role->id));
    Sanctum::actingAs($user);

    $response = $this->get('/dashboard');

    $response->assertStatus(302);
});
