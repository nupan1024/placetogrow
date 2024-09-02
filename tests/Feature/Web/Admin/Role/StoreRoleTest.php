<?php

use App\Domain\Users\Models\User;
use App\Support\Definitions\Permissions;
use App\Support\Definitions\Roles;
use Database\Factories\PermissionFactory;
use Spatie\Permission\Models\Role;

test('store role success', function () {
    PermissionFactory::new()->createMany(Permissions::toArray());

    $role = new Role();
    $role->name = Roles::SUPER_ADMIN->name;
    $role->syncPermissions(Permissions::getPermissions());
    $role->save();

    $user = User::factory()->create([
        'role_id'
        => $role->id,
    ]);
    $user->assignRole(Role::findById($role->id));
    $name = fake()->name;
    $data = [
        'name' => $name,
        'permissions' => [Permissions::MICROSITES->value]
    ];

    $this->actingAs($user);
    $response = $this->post(route("role.store"), $data);
    $response->assertStatus(302);
    $response->assertRedirect(route('roles'));
    $response->assertSessionHas('message', __('roles.success_create'));
    $response->assertSessionHas('type', 'success');

    $this->assertDatabaseHas('roles', [
        'name' => $name,
    ]);
});

test('not store field because not receive every param', function () {
    PermissionFactory::new()->createMany(Permissions::toArray());

    $role = new Role();
    $role->name = Roles::SUPER_ADMIN->name;
    $role->syncPermissions(Permissions::getPermissions());
    $role->save();

    $user = User::factory()->create([
        'role_id' => $role->id,
    ]);
    $user->assignRole(Role::findById($role->id));

    $name = fake()->name;
    $data = [
        'name' => $name,
        'permissions' => []
    ];

    $this->actingAs($user);
    $response = $this->post(route("role.store"), $data);
    $response->assertStatus(302);
    $response->assertSessionHasErrors(['permissions']);

    $this->assertDatabaseMissing('roles', [
        'name' => $name,

    ]);
});
