<?php

use App\Domain\Fields\Models\Field;
use App\Domain\Microsites\Models\Microsite;
use App\Domain\Users\Models\User;
use App\Support\Definitions\Permissions;
use App\Support\Definitions\Roles;
use Database\Factories\PermissionFactory;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Models\Role;

test('delete field when user is super admin', function () {
    PermissionFactory::new()->createMany(Permissions::toArray());

    $role = new Role();
    $role->name = Roles::SUPER_ADMIN->name;
    $role->syncPermissions(Permissions::getPermissions());
    $role->save();

    $user = User::factory()->create([
        'role_id' => $role->id,
    ]);
    $user->assignRole(Role::findById($role->id));

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

    $role = new Role();
    $role->name = fake()->name;
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

    $role = new Role();
    $role->name = fake()->name;
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

test('delete field fails and redirects with error message', function () {
    PermissionFactory::new()->createMany(Permissions::toArray());

    $role = new Role();
    $role->name = Roles::SUPER_ADMIN->name;
    $role->syncPermissions(Permissions::getPermissions());
    $role->save();

    $user = User::factory()->create([
        'role_id' => $role->id,
    ]);
    $user->assignRole(Role::findById($role->id));

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
