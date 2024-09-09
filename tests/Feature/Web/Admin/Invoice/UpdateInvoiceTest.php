<?php

use App\Domain\Invoices\Models\Invoice;
use App\Domain\Microsites\Models\Microsite;
use App\Domain\Users\Models\User;
use App\Support\Definitions\Permissions;
use App\Support\Definitions\Roles;
use Database\Factories\PermissionFactory;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Models\Role;

test('update invoice success', function () {
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

    $invoice = Invoice::factory()->create();

    $value = fake()->numberBetween(1, 10000);
    $description = fake()->paragraph();

    $data = [
        'microsite_id' => $invoice->microsite_id,
        'user_id' => $invoice->user_id,
        'value' => $value,
        'description' => $description,
        'status' => $invoice->status,
        'code' => $invoice->code,
    ];

    $response = $this->post(route("invoice.update", $invoice->id), $data);

    $response->assertStatus(302);

    $response->assertRedirect(route('invoices'));

    $response->assertSessionHas('message', __('invoices.success_update'));
    $response->assertSessionHas('type', 'success');

    $this->assertDatabaseHas('invoices', [
        'microsite_id' => $invoice->microsite_id,
        'user_id' => $invoice->user_id,
        'value' => $value,
        'description' => $description,
        'status' => $invoice->status,
        'code' => $invoice->code,
    ]);
});

test('not update invoice because not receive every param', function () {
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

    $invoice = Invoice::factory()->create();

    $microsite = Microsite::factory()->create();
    $description = fake()->paragraph();
    $data = [
        'microsite_id' => $invoice->microsite_id,
        'user_id' => $invoice->user_id,
        'description' => $description,
        'status' => $invoice->status,
        'code' => $invoice->code,
    ];

    $response = $this->post(route("invoice.update", $invoice->id), $data);

    $response->assertStatus(302);

    $response->assertSessionHasErrors(['value']);

    $this->assertDatabaseMissing('invoices', [
        'microsite_id' => $invoice->microsite_id,
        'user_id' => $invoice->user_id,
        'description' => $description,
        'status' => $invoice->status,
        'code' => $invoice->code,
    ]);
});
