<?php

use App\Domain\Currencies\Models\Currency;
use App\Domain\Microsites\Models\Microsite;
use App\Domain\Subscriptions\Models\Subscription;
use App\Domain\Users\Models\User;
use App\Support\Definitions\BillingFrequency;
use App\Support\Definitions\Permissions;
use App\Support\Definitions\Roles;
use Database\Factories\PermissionFactory;
use Spatie\Permission\Models\Role;

test('update subscription', function () {
    PermissionFactory::new()->createMany(Permissions::toArray());

    $role = new Role();
    $role->name = Roles::SUPER_ADMIN->name;
    $role->syncPermissions(Permissions::getPermissions());
    $role->save();

    $user = User::factory()->create([
        'role_id' => $role->id,
    ]);
    $user->assignRole(Role::findById($role->id));

    $subscription = Subscription::factory()->create();
    $name = fake()->name();
    $description = fake()->paragraph();
    $status = fake()->boolean();
    $time_expire = fake()->dateTimeBetween('now', '+1 year')->format('Y-m-d');
    $amount = fake()->numberBetween(1000, 10000);
    $data = [
        'microsite_id' => $subscription->microsite_id,
        'currency_id' => $subscription->currency_id,
        'amount' => $amount,
        'description' => $description,
        'name' => $name,
        'status' => $status,
        'billing_frequency' => (string)BillingFrequency::MONTH->value,
        'time_expire' => $time_expire,
    ];

    $this->actingAs($user);
    $response = $this->patch(route("subscription.update", $subscription->id), $data);

    $response->assertStatus(302);

    $response->assertRedirect(route('subscriptions'));

    $response->assertSessionHas('message', __('subscriptions.success_update'));
    $response->assertSessionHas('type', 'success');

    $this->assertDatabaseHas('subscriptions', [
        'microsite_id' => $subscription->microsite_id,
        'currency_id' => $subscription->currency_id,
        'amount' => $amount,
        'description' => $description,
        'name' => $name,
        'status' => $status,
        'billing_frequency' => (string)BillingFrequency::MONTH->value,
        'time_expire' => $time_expire,
    ]);
});

test('not update subscription because not receive every param', function () {
    PermissionFactory::new()->createMany(Permissions::toArray());

    $role = new Role();
    $role->name = Roles::SUPER_ADMIN->name;
    $role->syncPermissions(Permissions::getPermissions());
    $role->save();

    $user = User::factory()->create([
        'role_id' => $role->id,
    ]);
    $user->assignRole(Role::findById($role->id));

    $subscription = Subscription::factory()->create();
    $name = fake()->name();
    $description = fake()->paragraph();
    $status = fake()->boolean();
    $time_expire = fake()->dateTimeBetween('now', '+1 year')->format('Y-m-d');
    $data = [
        'microsite_id' => $subscription->microsite_id,
        'currency_id' => $subscription->currency_id,
        'description' => $description,
        'name' => $name,
        'status' => $status,
        'billing_frequency' => (string)BillingFrequency::MONTH->value,
        'time_expire' => $time_expire,
    ];

    $this->actingAs($user);
    $response = $this->patch(route("subscription.update", $subscription->id), $data);

    $response->assertStatus(302);

    $response->assertSessionHasErrors(['amount']);

    $this->assertDatabaseMissing('subscriptions', [
        'microsite_id' => $subscription->microsite_id,
        'currency_id' => $subscription->currency_id,
        'description' => $description,
        'name' => $name,
        'status' => $status,
        'billing_frequency' => (string)BillingFrequency::MONTH->value,
        'time_expire' => $time_expire,
    ]);
});
