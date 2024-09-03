<?php

use App\Domain\Invoices\Models\Invoice;
use App\Domain\Microsites\Models\Microsite;
use App\Domain\Users\Models\User;
use App\Support\Definitions\Permissions;
use App\Support\Definitions\Roles;
use Database\Factories\PermissionFactory;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Models\Role;

test('delete microsite when user is super admin', function () {
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

    $microsite = Microsite::factory()->create();

    $response = $this->delete(route('microsite.delete', $microsite->id));

    $response->assertStatus(302);

    $response->assertRedirect(route('microsites'));

    $this->assertDatabaseMissing('microsites', ['id' => $microsite->id]);
});

test('delete microsite when user has permission', function () {
    PermissionFactory::new()->createMany(Permissions::toArray());

    $role = new Role();
    $role->name = fake()->name();
    $role->syncPermissions([Permissions::DELETE_MICROSITE->value]);
    $role->save();

    $user = User::factory()->create([
        'role_id' => $role->id,
    ]);
    $user->assignRole($role->id);
    Sanctum::actingAs($user);

    $microsite = Microsite::factory()->create();
    $response = $this->delete(route('microsite.delete', $microsite->id));
    $response->assertStatus(302);

    $response->assertRedirect(route('microsites'));

    $this->assertDatabaseMissing('microsites', ['id' => $microsite->id]);
});

test('delete microsite when user does not have permission', function () {
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

    $microsite = Microsite::factory()->create();
    $response = $this->delete(route('microsite.delete', $microsite->id));
    $response->assertStatus(403);
});

test('Could not delete microsite because has invoices', function () {
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

    $microsite = Microsite::factory()->create();
    Invoice::factory()->create([
        'microsite_id' => $microsite->id,
    ]);

    $response = $this->delete(route('microsite.delete', $microsite));

    $response->assertStatus(302);
    $response->assertRedirect(route('microsites'));

    $response->assertSessionHas('message', __('microsites.invoices_error'));
    $response->assertSessionHas('type', 'error');

    $this->assertDatabaseHas('microsites', [
        'id' => $microsite->id,
    ]);
});
