<?php

use App\Domain\Users\Models\User;
use App\Support\Definitions\Permissions;
use App\Support\Definitions\Roles;
use Database\Factories\PermissionFactory;
use Illuminate\Support\Facades\Artisan;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Models\Role;

test('view create field when user is super admin', function () {
    PermissionFactory::new()->createMany(Permissions::toArray());

    Artisan::call('db:seed', ['--class' => 'RoleSeeder']);
    $user = User::factory()->create([
        'role_id' => Roles::SUPER_ADMIN->value,
    ]);
    $user->assignRole(Role::findById(Roles::SUPER_ADMIN->value)->name);

    Sanctum::actingAs($user);
    $response = $this->get(route("field.create"));

    $response->assertStatus(200);
});

test('view create field when user has permission', function () {
    PermissionFactory::new()->createMany(Permissions::toArray());

    Artisan::call('db:seed', ['--class' => 'RoleSeeder']);

    $role = new Role();
    $role->name = 'Role test';
    $role->syncPermissions([Permissions::CREATE_FIELD->value]);
    $role->save();

    $user = User::factory()->create([
        'role_id' => $role->id,
    ]);
    $user->assignRole($role->id);

    Sanctum::actingAs($user);
    $response = $this->get(route("field.create"));

    $response->assertStatus(200);
});

test('view create field when user does not have permission', function () {
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
    $response = $this->get(route("field.create"));

    $response->assertStatus(403);
});


test('store field', function () {
    PermissionFactory::new()->createMany(Permissions::toArray());

    Artisan::call('db:seed', ['--class' => 'RoleSeeder']);

    $user = User::factory()->create([
        'role_id' => Roles::SUPER_ADMIN->value,
    ]);
    $user->assignRole(Role::findById(Roles::SUPER_ADMIN->value)->name);

    Sanctum::actingAs($user);

    $data = [
        'name' => 'text_field',
        'label' => 'Text field',
        'type' => 'input',
    ];

    $response = $this->post(route("field.store"), $data);

    $response->assertStatus(302);

    $response->assertRedirect(route('fields'));

    $response->assertSessionHas('message', __('fields.success_create'));
    $response->assertSessionHas('type', 'success');

    $this->assertDatabaseHas('fields', [
        'name' => 'text_field',
        'label' => 'Text field',
        'type' => 'input',
    ]);
});

test('not store field because not receive every param', function () {
    PermissionFactory::new()->createMany(Permissions::toArray());

    Artisan::call('db:seed', ['--class' => 'RoleSeeder']);

    $user = User::factory()->create([
        'role_id' => Roles::SUPER_ADMIN->value,
    ]);
    $user->assignRole(Role::findById(Roles::SUPER_ADMIN->value)->name);

    Sanctum::actingAs($user);

    $data = [
        'name' => 'text_field',
        'label' => 'Text field',
    ];

    $response = $this->post('/field/store', $data);

    $response->assertStatus(302);

    $response->assertSessionHasErrors(['type']);

    $this->assertDatabaseMissing('fields', [
        'name' => 'text_field',
        'label' => 'Text field',
    ]);
});


