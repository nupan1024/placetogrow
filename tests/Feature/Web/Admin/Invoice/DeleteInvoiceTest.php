<?php

use App\Domain\Invoices\Models\Invoice;
use App\Domain\Users\Models\User;
use App\Support\Definitions\Permissions;
use App\Support\Definitions\Roles;
use App\Support\Definitions\StatusInvoices;
use Database\Factories\PermissionFactory;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Models\Role;

test('delete invoice when user is super admin', function () {
    PermissionFactory::new()->createMany(Permissions::toArray());

    $role = new Role();
    $role->name = Roles::SUPER_ADMIN->name;
    $role->syncPermissions(Permissions::getPermissions());
    $role->save();

    $user = User::factory()->create([
        'role_id' => $role->id,
    ]);
    $user->assignRole($role->id);

    Sanctum::actingAs($user);

    $invoice = Invoice::factory()->create();

    $response = $this->delete(route('invoice.delete', $invoice->id));

    $response->assertStatus(302);

    $response->assertRedirect(route('invoices'));

    $response->assertSessionHas('message', __('invoices.success_delete'));
    $response->assertSessionHas('type', 'success');

    $this->assertDatabaseMissing('invoices', ['id' => $invoice->id]);
});

test('delete invoice when user has permission', function () {
    PermissionFactory::new()->createMany(Permissions::toArray());

    $role = new Role();
    $role->name = fake()->name();
    $role->syncPermissions([Permissions::DELETE_INVOICE->value]);
    $role->save();

    $user = User::factory()->create([
        'role_id' => $role->id,
    ]);
    $user->assignRole($role->id);
    Sanctum::actingAs($user);

    $invoice = Invoice::factory()->create();
    $response = $this->delete(route('invoice.delete', $invoice->id));
    $response->assertStatus(302);

    $response->assertRedirect(route('invoices'));

    $response->assertSessionHas('message', __('invoices.success_delete'));
    $response->assertSessionHas('type', 'success');

    $this->assertDatabaseMissing('invoices', ['id' => $invoice->id]);
});

test('delete invoice when user does not have permission', function () {
    PermissionFactory::new()->createMany(Permissions::toArray());

    $role = new Role();
    $role->name = fake()->name();
    $role->syncPermissions([Permissions::CREATE_INVOICE->value]);
    $role->save();

    $user = User::factory()->create([
        'role_id' => $role->id,
    ]);
    $user->assignRole($role->id);
    Sanctum::actingAs($user);

    $invoice = Invoice::factory()->create();
    $response = $this->delete(route('invoice.delete', $invoice->id));
    $response->assertStatus(403);
});

test('Could not delete invoice because it status is different from pending', function () {
    PermissionFactory::new()->createMany(Permissions::toArray());

    $role = new Role();
    $role->name = fake()->name();
    $role->syncPermissions(Permissions::getPermissions());
    $role->save();

    $user = User::factory()->create([
        'role_id' => $role->id,
    ]);
    $user->assignRole(Role::findById($role->id));

    Sanctum::actingAs($user);

    $invoice = Invoice::factory()->create([
        'status' => StatusInvoices::PAID->name
    ]);


    $response = $this->delete(route('invoice.delete', $invoice));

    $response->assertStatus(302);
    $response->assertRedirect(route('invoices'));

    $response->assertSessionHas('message', __('invoices.error_delete'));
    $response->assertSessionHas('type', 'error');

    $this->assertDatabaseHas('invoices', [
        'id' => $invoice->id,
    ]);
});
