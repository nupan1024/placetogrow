<?php

use App\Domain\Roles\Actions\UpdateRole;
use Spatie\Permission\Models\Role;
use App\Domain\Users\Models\Role as ModelsRole;
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

    expect(UpdateRole::execute($params, $role))->toBeTrue();
});


test('Generate error with permissions not found', function () {

    $role = ModelsRole::factory()->make();
    $params = [
        'name' => 'Role test',
        'permissions' => [Permissions::DELETE_INVOICE->value, Permissions::INVOICES->value],
    ];
    expect(UpdateRole::execute($params, $role))->toBeFalse();
});
