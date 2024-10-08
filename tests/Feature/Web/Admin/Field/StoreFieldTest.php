<?php

use App\Domain\Users\Models\User;
use App\Support\Definitions\Permissions;
use App\Support\Definitions\Roles;
use Database\Factories\PermissionFactory;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Models\Role;

test('store field', function () {
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

    $role = new Role();
    $role->name = Roles::SUPER_ADMIN->name;
    $role->syncPermissions(Permissions::getPermissions());
    $role->save();

    $user = User::factory()->create([
        'role_id' => $role->id,
    ]);
    $user->assignRole(Role::findById($role->id));

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
