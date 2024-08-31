<?php

use App\Domain\Users\Actions\GetUsersByRole;
use App\Domain\Users\Models\Role;

test('get users by role', function () {
    $role = Role::factory()->create();
    $params = [
        'role' => $role->id,
    ];
    expect(GetUsersByRole::execute($params))->toBeObject();
});

