<?php

use App\Domain\Microsites\Models\Microsite;
use App\Domain\Users\Models\User;
use App\Support\Definitions\Permissions;
use App\Support\Definitions\Roles;
use Database\Factories\PermissionFactory;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Models\Role;

test('update microsite success', function () {
    PermissionFactory::new()->createMany(Permissions::toArray());

    $role = new Role();
    $role->name = Roles::SUPER_ADMIN->name;
    $role->syncPermissions(Permissions::getPermissions());
    $role->save();

    $user = User::factory()->create([
        'role_id' => $role->id,
    ]);
    $user->assignRole(Role::findById($role->id));

    $status = fake()->boolean() ? 1 : 0;
    $microsite = Microsite::factory()->create([
        'status' => $status,
    ]);
    $name = fake()->name();
    $description = fake()->paragraph();

    $data = [
        'category_id' => $microsite->category_id,
        'name' => $name,
        'description' => $description,
        'logo_path' => $microsite->logo_path,
        'currency_id' =>  $microsite->currency_id,
        'microsites_type_id' =>  $microsite->microsites_type_id,
        'status' => $status,
        'fields' => $microsite->fields,
    ];

    $this->actingAs($user);
    $response = $this->patch(route("microsite.update", $microsite->id), $data);

    $response->assertStatus(302);
    $response->assertRedirect(route('microsites'));
    $response->assertSessionHas('message', __('microsites.success_update'));
    $response->assertSessionHas('type', 'success');

    $this->assertDatabaseHas('microsites', [
        'id' => $microsite->id,
        'category_id' => $microsite->category_id,
        'name' => $name,
        'description' => $description,
        'microsites_type_id' =>  $microsite->microsites_type_id,
        'status' => $status,
    ]);
});

test('not update microsite because not receive every param', function () {
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

    $status = fake()->boolean() ? 1 : 0;
    $microsite = Microsite::factory()->create([
        'status' => $status,
    ]);

    $description = fake()->paragraph();
    $data = [
        'category_id' => $microsite->category_id,
        'description' => $description,
        'logo_path' => $microsite->logo_path,
        'currency_id' =>  $microsite->currency_id,
        'microsites_type_id' =>  $microsite->microsites_type_id,
        'status' => $status,
        'fields' => $microsite->fields,
    ];

    $response = $this->patch(route("microsite.update", $microsite->id), $data);

    $response->assertStatus(302);

    $response->assertSessionHasErrors(['name']);

    $this->assertDatabaseMissing('microsites', [
        'category_id' => $microsite->category_id,
        'description' => $description,
        'logo_path' => $microsite->logo_path,
        'currency_id' =>  $microsite->currency_id,
        'microsites_type_id' =>  $microsite->microsites_type_id,
        'status' => $status,
        'fields' => $microsite->fields,
    ]);
});
