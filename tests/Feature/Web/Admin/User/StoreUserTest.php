<?php

use App\Domain\Users\Models\User;
use App\Support\Definitions\Permissions;
use App\Support\Definitions\Roles;
use App\Support\Definitions\Status;
use Database\Factories\PermissionFactory;
use Spatie\Permission\Models\Role;

test('store user success', function () {
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
    $email = fake()->email();
    $password = fake()->password(8);
    $data = [
        'name' => $name,
        'email' => $email,
        'password' => $password,
        'password_confirmation' => $password,
        'status' => Status::ACTIVE->value,
        'role_id' => $role->id,
    ];

    $this->actingAs($user);

    $response = $this->post(route("user.store"), $data);
    $response->assertStatus(302);
    $response->assertRedirect(route('users'));
    $response->assertSessionHas('message', __('users.success_create'));
    $response->assertSessionHas('type', 'success');

    $this->assertDatabaseHas('users', [
        'name' => $name,
        'email' => $email,
        'status' => Status::ACTIVE->value,
        'role_id' => $role->id,
    ]);
});

test('not store user because not receive every param', function () {
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
    $password = fake()->password();
    $data = [
        'name' => $name,
        'password' => $password,
        'password_confirmation' => $password,
        'status' => Status::ACTIVE->value,
        'role_id' => $role->id,
    ];

    $this->actingAs($user);
    $response = $this->post(route("user.store"), $data);

    $response->assertStatus(302);

    $response->assertSessionHasErrors(['email']);

    $this->assertDatabaseMissing('users', [
        'name' => $name,
        'status' => Status::ACTIVE->value,
        'role_id' => $role->id,
    ]);
});
