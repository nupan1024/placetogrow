<?php

use App\Domain\Users\Models\User;
use App\Support\Definitions\Permissions;
use App\Support\Definitions\Roles;
use App\Support\Definitions\Status;
use Database\Factories\PermissionFactory;
use Spatie\Permission\Models\Role;

test('store update success', function () {
    PermissionFactory::new()->createMany(Permissions::toArray());

    $role = new Role();
    $role->name = Roles::SUPER_ADMIN->name;
    $role->syncPermissions(Permissions::getPermissions());
    $role->save();

    $user = User::factory()->create([
        'role_id' => $role->id,
    ]);
    $user->assignRole(Role::findById($role->id));

    $testUser = User::factory()->create();
    $email = fake()->email();
    $data = [
        'name' => $testUser->name,
        'email' => $email,
        'status' => Status::ACTIVE->value,
        'role_id' => $testUser->role_id,
    ];

    $this->actingAs($user);

    $response = $this->patch(route("user.update", $testUser->id), $data);
    $response->assertStatus(302);
    $response->assertRedirect(route('users'));
    $response->assertSessionHas('message', __('users.success_update'));
    $response->assertSessionHas('type', 'success');

    $this->assertDatabaseHas('users', [
        'name' => $testUser->name,
        'email' => $email,
        'status' => Status::ACTIVE->value,
        'role_id' => $testUser->role_id,
    ]);
});

test('not update user because not receive every param', function () {
    PermissionFactory::new()->createMany(Permissions::toArray());

    $role = new Role();
    $role->name = Roles::SUPER_ADMIN->name;
    $role->syncPermissions(Permissions::getPermissions());
    $role->save();

    $user = User::factory()->create([
        'role_id' => $role->id,
    ]);
    $user->assignRole(Role::findById($role->id));

    $testUser = User::factory()->create();
    $email = fake()->email();
    $data = [
        'email' => $email,
        'status' => Status::ACTIVE->value,
        'role_id' => $testUser->role_id,
    ];

    $this->actingAs($user);
    $response = $this->patch(route("user.update", $testUser->id), $data);

    $response->assertStatus(302);

    $response->assertSessionHasErrors(['name']);

    $this->assertDatabaseMissing('users', [
        'email' => $email,
        'status' => Status::ACTIVE->value,
        'role_id' => $testUser->role_id,
    ]);
});
