<?php

use App\Domain\Roles\Actions\CreateRole;
use App\Support\Definitions\Permissions;
use Database\Factories\PermissionFactory;

test('create role', function () {
    PermissionFactory::new()->createMany(Permissions::toArray());
    $params = [
        'name' => 'Role test',
        'permissions' => [Permissions::DELETE_INVOICE->value],
    ];

    expect(CreateRole::execute($params))->toBeTrue();
});

test('Does not receive permissions', function () {
    $params = [
        'name' => 'Role test',
    ];

    expect(CreateRole::execute($params))->toBeFalse();
});
