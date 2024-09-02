<?php

use App\Domain\Users\Models\User;
use App\Support\Definitions\Permissions;
use App\Support\Definitions\Roles;
use Database\Factories\PermissionFactory;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Role;

test('delete role when user is super admin', function () {
    PermissionFactory::new()->createMany(Permissions::toArray());
    Artisan::call('db:seed', ['--class' => 'RoleSeeder']);


    $user = User::factory()->create([
        'role_id' => Roles::SUPER_ADMIN->value,
    ]);
    $user->assignRole(Roles::SUPER_ADMIN->value);


    $testRole = new Role();
    $testRole->name = fake()->name();
    $testRole->syncPermissions(Permissions::getPermissions());
    $testRole->save();

    $this->actingAs($user);
    $response = $this->delete(route('role.delete', $testRole->id));

    $response->assertStatus(302);

    $response->assertRedirect(route('roles'));

    $this->assertDatabaseMissing('roles', ['id' => $testRole->id]);
});

test('delete role when user has permission', function () {
    PermissionFactory::new()->createMany(Permissions::toArray());
    Artisan::call('db:seed', ['--class' => 'RoleSeeder']);

    $role = new Role();
    $role->name = fake()->name();
    $role->syncPermissions([Permissions::DELETE_ROLE->value]);
    $role->save();

    $user = User::factory()->create([
        'role_id' => $role->id,
    ]);
    $user->assignRole($role->id);

    $testRole = new Role();
    $testRole->name = fake()->name();
    $testRole->syncPermissions(Permissions::getPermissions());
    $testRole->save();

    $this->actingAs($user);
    $response = $this->delete(route('role.delete', $testRole->id));
    $response->assertStatus(302);

    $response->assertRedirect(route('roles'));

    $this->assertDatabaseMissing('roles', ['id' => $testRole->id]);
});

test('delete role when user does not have permission', function () {
    PermissionFactory::new()->createMany(Permissions::toArray());

    $role = new Role();
    $role->name = fake()->name();
    $role->syncPermissions([Permissions::CREATE_INVOICE->value]);
    $role->save();

    $user = User::factory()->create([
        'role_id' => $role->id,
    ]);
    $user->assignRole($role->id);

    $testRole = new Role();
    $testRole->name = fake()->name();
    $testRole->syncPermissions(Permissions::getPermissions());
    $testRole->save();

    $this->actingAs($user);
    $response = $this->delete(route('role.delete', $testRole->id));
    $response->assertStatus(403);
});

test('Could not delete role because there are users with this role', function () {
    PermissionFactory::new()->createMany(Permissions::toArray());
    Artisan::call('db:seed', ['--class' => 'RoleSeeder']);

    $role = new Role();
    $role->name = fake()->name();
    $role->syncPermissions(Permissions::getPermissions());
    $role->save();

    $user = User::factory()->create([
        'role_id' => $role->id,
    ]);
    $user->assignRole(Role::findById($role->id));

    $testRole = new Role();
    $testRole->name = fake()->name();
    $testRole->syncPermissions(Permissions::getPermissions());
    $testRole->save();

    User::factory()->create([
        'role_id' => $testRole->id,
    ]);

    $this->actingAs($user);
    $response = $this->delete(route('role.delete', $testRole->id));

    $response->assertStatus(302);
    $response->assertRedirect(route('roles'));

    $response->assertSessionHas('message', __('roles.error_delete'));
    $response->assertSessionHas('type', 'error');

    $this->assertDatabaseHas('roles', [
        'id' => $testRole->id,
    ]);
});
