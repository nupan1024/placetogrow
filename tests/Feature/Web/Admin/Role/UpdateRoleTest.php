<?php

use App\Domain\Users\Models\User;
use App\Support\Definitions\Permissions;
use App\Support\Definitions\Roles;
use Database\Factories\PermissionFactory;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Role;

test('update role success', function () {
    PermissionFactory::new()->createMany(Permissions::toArray());
    Artisan::call('db:seed', ['--class' => 'RoleSeeder']);

    $user = User::factory()->create([
        'role_id' => Roles::SUPER_ADMIN->value,
    ]);
    $user->assignRole(Role::findById(Roles::SUPER_ADMIN->value));
    $name = fake()->name;

    $testRole = new Role();
    $testRole->name = $name;
    $testRole->syncPermissions([Permissions::MICROSITES->value]);
    $testRole->save();

    $this->actingAs($user);
    $data = [
        'name' => $name,
        'permissions' => [
            Permissions::MICROSITES->value, Permissions::DELETE_MICROSITE->value
        ]
    ];

    $response = $this->patch(route("role.update", $testRole->id), $data);
    $response->assertStatus(302);
    $response->assertRedirect(route('roles'));
    $response->assertSessionHas('message', __('roles.success_update'));
    $response->assertSessionHas('type', 'success');

    $this->assertDatabaseHas('roles', [
        'name' => $name,
    ]);
});

test('not update field because not receive every param', function () {
    PermissionFactory::new()->createMany(Permissions::toArray());
    Artisan::call('db:seed', ['--class' => 'RoleSeeder']);

    $user = User::factory()->create([
        'role_id' => Roles::SUPER_ADMIN->value,
    ]);
    $user->assignRole(Role::findById(Roles::SUPER_ADMIN->value));

    $name = fake()->name;
    $data = [
        'name' => $name,
        'permissions' => []
    ];

    $this->actingAs($user);
    $response = $this->post(route("role.store"), $data);
    $response->assertStatus(302);
    $response->assertSessionHasErrors(['permissions']);

    $this->assertDatabaseMissing('roles', [
        'name' => $name,

    ]);
});
