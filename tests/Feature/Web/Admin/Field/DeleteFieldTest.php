<?php

use App\Domain\Fields\Actions\DeleteField;
use App\Domain\Fields\Models\Field;
use App\Domain\Microsites\Models\Microsite;
use App\Domain\Users\Models\User;
use App\Support\Definitions\Permissions;
use App\Support\Definitions\Roles;
use Database\Factories\PermissionFactory;
use Illuminate\Support\Facades\Artisan;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Models\Role;

test('delete field when user is super admin', function () {
    PermissionFactory::new()->createMany(Permissions::toArray());

    Artisan::call('db:seed', ['--class' => 'RoleSeeder']);

    $user = User::factory()->create([
        'role_id' => Roles::SUPER_ADMIN->value,
    ]);
    $user->assignRole(Role::findById(Roles::SUPER_ADMIN->value)->name);

    Sanctum::actingAs($user);

    $field = Field::factory()->create();

    $response = $this->delete(route('field.delete', $field->id));

    $response->assertStatus(302);

    $response->assertRedirect(route('fields'));

    $response->assertSessionHas('message', __('fields.success_delete'));
    $response->assertSessionHas('type', 'success');

    $this->assertDatabaseMissing('fields', ['id' => $field->id]);
});

test('delete field when user has permission', function () {
    PermissionFactory::new()->createMany(Permissions::toArray());

    Artisan::call('db:seed', ['--class' => 'RoleSeeder']);

    $role = new Role();
    $role->name = 'Role test';
    $role->syncPermissions([Permissions::DELETE_FIELD->value]);
    $role->save();

    $user = User::factory()->create([
        'role_id' => $role->id,
    ]);
    $user->assignRole($role->id);
    Sanctum::actingAs($user);

    $field = Field::factory()->create();
    $response = $this->delete(route('field.delete', $field->id));
    $response->assertStatus(302);

    $response->assertRedirect(route('fields'));

    $response->assertSessionHas('message', __('fields.success_delete'));
    $response->assertSessionHas('type', 'success');

    $this->assertDatabaseMissing('fields', ['id' => $field->id]);
});

test('delete field when user does not have permission', function () {
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
    $response = $this->delete(route('field.delete', $field->id));
    $response->assertStatus(403);
});

test('not delete field because not receive every param', function () {
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

    $response = $this->post(route("field.store"), $data);

    $response->assertStatus(302);

    $response->assertSessionHasErrors(['type']);

    $this->assertDatabaseMissing('fields', [
        'name' => 'text_field',
        'label' => 'Text field',
    ]);
});

test('delete field fails and redirects with error message', function () {
    PermissionFactory::new()->createMany(Permissions::toArray());

    Artisan::call('db:seed', ['--class' => 'RoleSeeder']);

    $user = User::factory()->create([
        'role_id' => Roles::SUPER_ADMIN->value,
    ]);
    $user->assignRole(Role::findById(Roles::SUPER_ADMIN->value)->name);

    Sanctum::actingAs($user);

    $field = Field::factory()->create();
    Microsite::factory()->create([
        'fields' => [$field->name],
    ]);

    $response = $this->delete(route('field.delete', $field));

    $response->assertStatus(302);
    $response->assertRedirect(route('fields'));

    $response->assertSessionHas('message', __('fields.error_delete'));
    $response->assertSessionHas('type', 'error');

    $this->assertDatabaseHas('fields', [
        'id' => $field->id,
    ]);
});
