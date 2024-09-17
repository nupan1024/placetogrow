<?php

use App\Domain\Fields\Models\Field;
use App\Domain\Users\Models\User;
use App\Support\Definitions\FieldsTypes;
use App\Support\Definitions\Permissions;
use App\Support\Definitions\Roles;
use Database\Factories\PermissionFactory;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Models\Role;

test('update field successfully', function () {
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
    $label = Str::limit(fake()->unique()->name(), 10);
    $type = FieldsTypes::TEXT_INPUT->value;

    $data = [
        'label' => $label,
        'type' => $type,
    ];

    $response = $this->post(route('field.update', $field), $data);
    $response->assertStatus(302);
    $response->assertRedirect(route('fields'));

    $response->assertSessionHas('message', __('fields.success_update'));
    $response->assertSessionHas('type', 'success');

    $this->assertDatabaseHas('fields', [
        'id' => $field->id,
        'label' => $label,
        'type' => $type,
    ]);
});

test('fail to update field due to missing required data', function () {
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

    $name = Str::snake(fake()->words(3, true));
    $label = Str::limit(fake()->unique()->name(), 10);
    $type = FieldsTypes::TEXT_INPUT->value;

    $field = Field::factory()->create([
        'name' => $name,
        'label' => $label,
        'type' => $type,
    ]);

    $data = [
        'label' => Str::limit(fake()->unique()->name(), 10),
    ];

    $response = $this->post(route('field.update', $field), $data);

    $response->assertStatus(302);

    $response->assertSessionHasErrors(['type']);

    $this->assertDatabaseHas('fields', [
        'id' => $field->id,
        'name' => $name,
        'label' => $label,
        'type' => $type,
    ]);
});
