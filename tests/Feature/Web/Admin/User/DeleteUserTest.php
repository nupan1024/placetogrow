<?php

use App\Domain\Users\Models\User;
use App\Support\Definitions\Permissions;
use App\Support\Definitions\Roles;
use Database\Factories\PermissionFactory;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Models\Role;

test('delete user when user is super admin', function () {
    PermissionFactory::new()->createMany(Permissions::toArray());

    $role = new Role();
    $role->name = Roles::SUPER_ADMIN->name;
    $role->syncPermissions(Permissions::getPermissions());
    $role->save();

    $user = User::factory()->create([
        'role_id' => $role->id,
    ]);
    $user->assignRole($role->id);

    $testUser = User::factory()->create();

    Sanctum::actingAs($user);
    $response = $this->delete(route('user.delete', $testUser->id));

    $response->assertStatus(302);

    $response->assertRedirect(route('users'));

    $this->assertDatabaseMissing('users', ['id' => $testUser->id]);
});

test('delete user when user has permission', function () {
    PermissionFactory::new()->createMany(Permissions::toArray());

    $role = new Role();
    $role->name = fake()->name();
    $role->syncPermissions([Permissions::DELETE_USER->value]);
    $role->save();

    $user = User::factory()->create([
        'role_id' => $role->id,
    ]);
    $user->assignRole($role->id);
    Sanctum::actingAs($user);

    $testUser = User::factory()->create();
    $response = $this->delete(route('user.delete', $testUser->id));
    $response->assertStatus(302);

    $response->assertRedirect(route('users'));

    $this->assertDatabaseMissing('users', ['id' => $testUser->id]);
});

test('delete user when user does not have permission', function () {
    PermissionFactory::new()->createMany(Permissions::toArray());

    $role = new Role();
    $role->name = fake()->name();
    $role->syncPermissions([Permissions::CREATE_INVOICE->value]);
    $role->save();

    $user = User::factory()->create([
        'role_id' => $role->id,
    ]);
    $user->assignRole($role->id);
    Sanctum::actingAs($user);

    $testUser = User::factory()->create();
    $response = $this->delete(route('user.delete', $testUser->id));
    $response->assertStatus(403);
});
