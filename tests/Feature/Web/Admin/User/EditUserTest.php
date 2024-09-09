<?php

use App\Domain\Users\Models\User;
use App\Support\Definitions\Permissions;
use Database\Factories\PermissionFactory;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Models\Role;

test('view edit user when user is super admin', function () {
    PermissionFactory::new()->createMany(Permissions::toArray());

    $role = new Role();
    $role->name = fake()->name;
    $role->syncPermissions(Permissions::getPermissions());
    $role->save();

    $user = User::factory()->create([
        'role_id' => $role->id,
    ]);
    $user->assignRole($role->id);
    Sanctum::actingAs($user);

    $testUser = User::factory()->create();
    $response = $this->get(route("user.edit", $testUser->id));

    $response->assertStatus(200);
});

test('view edit user when user has permission', function () {
    PermissionFactory::new()->createMany(Permissions::toArray());

    $role = new Role();
    $role->name = fake()->name;
    $role->syncPermissions([Permissions::UPDATE_USER->value]);
    $role->save();

    $user = User::factory()->create([
        'role_id' => $role->id,
    ]);
    $user->assignRole($role->id);
    Sanctum::actingAs($user);

    $testUser = User::factory()->create();
    $response = $this->get(route("user.edit", $testUser->id));

    $response->assertStatus(200);
});

test('view create user when user does not have permission', function () {
    PermissionFactory::new()->createMany(Permissions::toArray());

    $role = new Role();
    $role->name = fake()->name;
    $role->syncPermissions([Permissions::FIELDS->value]);
    $role->save();

    $user = User::factory()->create([
        'role_id' => $role->id,
    ]);
    $user->assignRole($role->id);
    Sanctum::actingAs($user);

    $testUser = User::factory()->create();
    $response = $this->get(route("user.edit", $testUser->id));

    $response->assertStatus(403);
});
