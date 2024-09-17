<?php

use App\Domain\Microsites\Models\Microsite;
use App\Domain\Users\Models\User;
use App\Support\Definitions\Permissions;
use App\Support\Definitions\Roles;
use App\Support\Definitions\StatusInvoices;
use Database\Factories\PermissionFactory;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Models\Role;

test('store invoice success', function () {
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

    $microsite = Microsite::factory()->create();

    $value = fake()->numberBetween(1, 10000);
    $description = fake()->paragraph();
    $code = 'MICROSITE_PLACETOGROW_'.time();
    $data = [
        'microsite_id' => $microsite->id,
        'user_id' => $user->id,
        'value' => $value,
        'description' => $description,
        'status' => StatusInvoices::PENDING->name,
    ];

    $response = $this->post(route("invoice.store"), $data);

    $response->assertStatus(302);

    $response->assertRedirect(route('invoices'));

    $response->assertSessionHas('message', __('invoices.success_create'));
    $response->assertSessionHas('type', 'success');

    $this->assertDatabaseHas('invoices', [
        'microsite_id' => $microsite->id,
        'user_id' => $user->id,
        'value' => $value,
        'description' => $description,
        'status' => StatusInvoices::PENDING->name,
    ]);
});

test('not store invoice because not receive every param', function () {
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

    $microsite = Microsite::factory()->create();

    $description = fake()->paragraph();
    $code = 'MICROSITE_PLACETOGROW_'.time();
    $data = [
        'microsite_id' => $microsite->id,
        'user_id' => $user->id,
        'description' => $description,
        'status' => StatusInvoices::PENDING->name,
        'code' => $code,
    ];

    $response = $this->post(route("invoice.store"), $data);

    $response->assertStatus(302);

    $response->assertSessionHasErrors(['value']);

    $this->assertDatabaseMissing('invoices', [
        'microsite_id' => $microsite->id,
        'user_id' => $user->id,
        'status' => StatusInvoices::PENDING->name,
        'code' => $code,
    ]);
});
