<?php

use App\Domain\Fields\Models\Field;
use App\Domain\Users\Models\User;
use App\Support\Definitions\Permissions;
use App\Support\Definitions\Roles;
use Database\Factories\PermissionFactory;
use Illuminate\Support\Facades\Artisan;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Models\Role;

test('view edit field when user is super admin', function () {
    PermissionFactory::new()->createMany(Permissions::toArray());

    Artisan::call('db:seed', ['--class' => 'RoleSeeder']);
    $user = User::factory()->create([
        'role_id' => Roles::SUPER_ADMIN->value,
    ]);
    $user->assignRole(Role::findById(Roles::SUPER_ADMIN->value)->name);
    Sanctum::actingAs($user);
    $field = Field::factory()->create();
    $response = $this->get('/field/' .  $field->id . '/edit');

    $response->assertStatus(200);
});

test('view edit field when user has permission', function () {
    PermissionFactory::new()->createMany(Permissions::toArray());

    Artisan::call('db:seed', ['--class' => 'RoleSeeder']);

    $role = new Role();
    $role->name = 'Role test';
    $role->syncPermissions([Permissions::UPDATE_FIELD->value]);
    $role->save();

    $user = User::factory()->create([
        'role_id' => $role->id,
    ]);
    $user->assignRole($role->id);
    Sanctum::actingAs($user);

    $field = Field::factory()->create();
    $response = $this->get('/field/' .  $field->id . '/edit');
    $response->assertStatus(200);
});

test('view edit field when user does not have permission', function () {
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

    $field = Field::factory()->create();
    $response = $this->get('/field/' .  $field->id . '/edit');
    $response->assertStatus(403);
});

test('update field successfully', function () {
    PermissionFactory::new()->createMany(Permissions::toArray());

    Artisan::call('db:seed', ['--class' => 'RoleSeeder']);

    $user = User::factory()->create([
        'role_id' => Roles::SUPER_ADMIN->value,
    ]);
    $user->assignRole(Role::findById(Roles::SUPER_ADMIN->value)->name);

    Sanctum::actingAs($user);

    $field = Field::factory()->create([
        'name' => 'initial_name',
        'label' => 'Initial Label',
        'type' => 'input',
    ]);

    $data = [
        'label' => 'Updated Label',
        'type' => 'textarea',
    ];

    $response = $this->post(route('field.update', $field), $data);
    $response->assertStatus(302);
    $response->assertRedirect(route('fields'));

    $response->assertSessionHas('message', __('fields.success_update'));
    $response->assertSessionHas('type', 'success');

    $this->assertDatabaseHas('fields', [
        'id' => $field->id,
        'label' => 'Updated Label',
        'type' => 'textarea',
    ]);
});

test('fail to update field due to missing required data', function () {
    PermissionFactory::new()->createMany(Permissions::toArray());

    Artisan::call('db:seed', ['--class' => 'RoleSeeder']);

    $user = User::factory()->create([
        'role_id' => Roles::SUPER_ADMIN->value,
    ]);
    $user->assignRole(Role::findById(Roles::SUPER_ADMIN->value)->name);

    Sanctum::actingAs($user);

    $field = Field::factory()->create([
        'name' => 'initial_name',
        'label' => 'Initial Label',
        'type' => 'input',
    ]);

    $data = [
        'label' => 'Updated Label',
    ];

    $response = $this->post(route('field.update', $field), $data);

    $response->assertStatus(302);

    $response->assertSessionHasErrors(['type']);

    $this->assertDatabaseHas('fields', [
        'id' => $field->id,
        'name' => 'initial_name',
        'label' => 'Initial Label',
        'type' => 'input',
    ]);
});
