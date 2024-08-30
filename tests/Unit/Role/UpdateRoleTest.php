<?php

use App\Domain\Roles\Actions\UpdateRole;
use Spatie\Permission\Models\Role;
use App\Support\Definitions\Permissions;
use Database\Factories\PermissionFactory;

test('update role', function () {
    PermissionFactory::new()->createMany(Permissions::toArray());
    $params = [
        'name' => 'Role test',
        'permissions' => [Permissions::DELETE_INVOICE->value, Permissions::INVOICES->value],
    ];

    $role = new Role();
    $role->name = 'Role test';
    $role->syncPermissions([Permissions::DELETE_INVOICE->value]);
    $role->save();

    expect(UpdateRole::execute([
        'fields' => $params,
        'role' => $role
    ]))->toBeTrue();
});

