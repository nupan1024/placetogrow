<?php

use App\Domain\Invoices\Models\Invoice;
use App\Domain\Users\Models\User;
use App\Support\Definitions\Permissions;
use Database\Factories\PermissionFactory;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Models\Role;

test('view edit invoice when user is super admin', function () {
    PermissionFactory::new()->createMany(Permissions::toArray());

    $role = new Role();
    $role->name = fake()->name;
    $role->syncPermissions(Permissions::getPermissions());
    $role->save();

    $user = User::factory()->create([
        'role_id' => $role->id,
    ]);
    $user->assignRole($role->id);
    Sanctum::actingAs($user);

    $invoice = Invoice::factory()->create();
    $response = $this->get(route("invoice.edit", $invoice->id));

    $response->assertStatus(200);
});

test('view edit invoice when user has permission', function () {
    PermissionFactory::new()->createMany(Permissions::toArray());

    $role = new Role();
    $role->name = fake()->name;
    $role->syncPermissions([Permissions::UPDATE_INVOICE->value]);
    $role->save();

    $user = User::factory()->create([
        'role_id' => $role->id,
    ]);
    $user->assignRole($role->id);
    Sanctum::actingAs($user);

    $invoice = Invoice::factory()->create();
    $response = $this->get(route("invoice.edit", $invoice->id));

    $response->assertStatus(200);
});

test('view create invoice when user does not have permission', function () {
    PermissionFactory::new()->createMany(Permissions::toArray());

    $role = new Role();
    $role->name = fake()->name;
    $role->syncPermissions([Permissions::FIELDS->value]);
    $role->save();

    $user = User::factory()->create([
        'role_id' => $role->id,
    ]);
    $user->assignRole($role->id);
    Sanctum::actingAs($user);

    $invoice = Invoice::factory()->create();
    $response = $this->get(route("invoice.edit", $invoice->id));

    $response->assertStatus(403);
});
