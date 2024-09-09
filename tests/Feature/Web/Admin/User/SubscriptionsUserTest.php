<?php

use App\Domain\Users\Models\User;
use App\Support\Definitions\Permissions;
use App\Support\Definitions\Roles;
use Database\Factories\PermissionFactory;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Models\Role;

test('view subscriptions for user when user is guest', function () {
    PermissionFactory::new()->createMany(Permissions::toArray());

    $role = new Role();
    $role->name = Roles::GUEST->name;
    $role->syncPermissions(Permissions::getPermissions());
    $role->save();

    $user = User::factory()->create([
        'role_id' => $role->id,
    ]);
    $user->assignRole(Role::findById($role->id));

    Sanctum::actingAs($user);

    $response = $this->get(route("user.subscriptions.list"));

    $response->assertStatus(200);
});
