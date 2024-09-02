<?php

use App\Domain\Users\Models\User;
use App\Support\Definitions\Permissions;
use App\Support\Definitions\Roles;
use Database\Factories\PermissionFactory;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Role;

test('view edit role when user is super admin', function () {
    PermissionFactory::new()->createMany(Permissions::toArray());
    Artisan::call('db:seed', ['--class' => 'RoleSeeder']);

    $user = User::factory()->create([
        'role_id' => Roles::SUPER_ADMIN->value,
    ]);
    $user->assignRole(Roles::SUPER_ADMIN->value);

    $testRole = new Role();
    $testRole->name = fake()->name();
    $testRole->syncPermissions([Permissions::UPDATE_USER->value]);
    $testRole->save();

    $this->actingAs($user);
    $response = $this->get(route("role.edit", $testRole->id));
    $response->assertStatus(200);
});

test('view edit role when user has permission', function () {
    PermissionFactory::new()->createMany(Permissions::toArray());
    Artisan::call('db:seed', ['--class' => 'RoleSeeder']);


    $role = new Role();
    $role->name = fake()->name;
    $role->syncPermissions([Permissions::UPDATE_ROLE->value]);
    $role->save();

    $user = User::factory()->create([
        'role_id' => $role->id,
    ]);
    $user->assignRole($role->id);

    $this->actingAs($user);
    $response = $this->get(route("role.edit", $role->id));

    $response->assertStatus(200);
});

test('view create role when user does not have permission', function () {
    PermissionFactory::new()->createMany(Permissions::toArray());
    Artisan::call('db:seed', ['--class' => 'RoleSeeder']);

    $role = new Role();
    $role->name = fake()->name;
    $role->syncPermissions([Permissions::FIELDS->value]);
    $role->save();

    $user = User::factory()->create([
        'role_id' => $role->id,
    ]);
    $user->assignRole($role->id);

    $testRole = new Role();
    $testRole->name = fake()->name();
    $testRole->syncPermissions([Permissions::UPDATE_USER->value]);
    $testRole->save();

    $this->actingAs($user);

    $response = $this->get(route("role.edit", $testRole->id));

    $response->assertStatus(403);
});
