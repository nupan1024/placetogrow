<?php

use App\Domain\Users\Models\User;
use App\Support\Definitions\Permissions;
use App\Support\Definitions\Roles;
use Database\Factories\PermissionFactory;
use Illuminate\Support\Facades\Artisan;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Models\Role;

test('view index invoices when user is super admin', function () {
    PermissionFactory::new()->createMany(Permissions::toArray());

    Artisan::call('db:seed', ['--class' => 'RoleSeeder']);

    $user = User::factory()->create([
        'role_id' => Roles::SUPER_ADMIN->value,
    ]);
    $user->assignRole(Role::findById(Roles::SUPER_ADMIN->value)->name);

    Sanctum::actingAs($user);

    $response = $this->get(route("invoices"));

    $response->assertStatus(200);
});

test('view index invoices when user has permission', function () {
    PermissionFactory::new()->createMany(Permissions::toArray());

    $role = new Role();
    $role->name = 'Role test';
    $role->syncPermissions([Permissions::INVOICES->value]);
    $role->save();

    $user = User::factory()->create([
        'role_id' => $role->id,
    ]);
    $user->assignRole($role->id);

    Sanctum::actingAs($user);

    $response = $this->get(route("invoices"));

    $response->assertStatus(200);
});

test('view index invoices when user does not have permission', function () {
    PermissionFactory::new()->createMany(Permissions::toArray());

    Artisan::call('db:seed', ['--class' => 'RoleSeeder']);

    $role = new Role();
    $role->name = 'Role test';
    $role->syncPermissions([Permissions::CREATE_INVOICE->value]);
    $role->save();

    $user = User::factory()->create([
        'role_id' => $role->id,
    ]);
    $user->assignRole($role->id);
    Sanctum::actingAs($user);

    $response = $this->get(route("invoices"));
    $response->assertStatus(403);
});
