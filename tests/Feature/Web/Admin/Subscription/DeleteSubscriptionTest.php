<?php

use App\Domain\Subscriptions\Models\Subscription;
use App\Domain\Microsites\Models\Microsite;
use App\Domain\Users\Models\User;
use App\Support\Definitions\Permissions;
use App\Support\Definitions\Roles;
use Database\Factories\PermissionFactory;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Models\Role;

test('delete subscription when user is super admin', function () {
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

    $subscription = Subscription::factory()->create();

    $response = $this->delete(route('subscription.delete', $subscription->id));

    $response->assertStatus(302);

    $response->assertRedirect(route('subscriptions'));

    $response->assertSessionHas('message', __('subscriptions.success_delete'));
    $response->assertSessionHas('type', 'success');

    $this->assertDatabaseMissing('subscriptions', ['id' => $subscription->id]);
});

test('delete subscription when user has permission', function () {
    PermissionFactory::new()->createMany(Permissions::toArray());

    $role = new Role();
    $role->name = fake()->name;
    $role->syncPermissions([Permissions::DELETE_SUBSCRIPTION->value]);
    $role->save();

    $user = User::factory()->create([
        'role_id' => $role->id,
    ]);
    $user->assignRole($role->id);
    Sanctum::actingAs($user);

    $subscription = Subscription::factory()->create();
    $response = $this->delete(route('subscription.delete', $subscription->id));
    $response->assertStatus(302);

    $response->assertRedirect(route('subscriptions'));

    $response->assertSessionHas('message', __('subscriptions.success_delete'));
    $response->assertSessionHas('type', 'success');

    $this->assertDatabaseMissing('subscriptions', ['id' => $subscription->id]);
});

test('delete subscription when user does not have permission', function () {
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

    $subscription = Subscription::factory()->create();
    $response = $this->delete(route('subscription.delete', $subscription->id));
    $response->assertStatus(403);
});
