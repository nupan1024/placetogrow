<?php

use App\Domain\Roles\Actions\DeleteRole;
use App\Domain\Users\Models\User;
use Spatie\Permission\Models\Role;
use App\Support\Definitions\Permissions;
use Database\Factories\PermissionFactory;

test('delete role', function () {
    PermissionFactory::new()->createMany(Permissions::toArray());
    $role = new Role();
    $role->name = 'Role test';
    $role->syncPermissions([Permissions::DELETE_INVOICE->value]);
    $role->save();

    expect(DeleteRole::execute([], $role))->toBeTrue();
});

test('Could not delete role because users has this role', function () {
    PermissionFactory::new()->createMany(Permissions::toArray());
    $role = new Role();
    $role->name = 'Role test';
    $role->syncPermissions([Permissions::DELETE_INVOICE->value]);
    $role->save();

    User::factory()->create([
        'role_id' => $role->id
    ]);

    expect(DeleteRole::execute([], $role))->toBeFalse();
});
