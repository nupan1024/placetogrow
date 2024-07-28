<?php

use App\Domain\Users\Actions\CreateUser;
use App\Domain\Users\Models\Role;
use App\Support\Definitions\Roles;
use App\Support\Definitions\Status;

test('create user', function () {
    $role = Role::factory()->create([
        'id' => Roles::ADMIN->value,
        'name' => Roles::ADMIN->name,
        'guard_name' => 'web',
    ]);

    $params = [
        'name' => 'User test',
        'email' => 'usertest@placetogrow.com',
        'role_id' => $role->id,
        'status' => Status::ACTIVE->value,
        'password' => '12345678',
    ];

    expect(CreateUser::execute($params))->toBeTrue();
});

test('generate expection', function () {
    $params = [
        'name' => 'User test',
        'email' => 'usertest@placetogrow.com',
        'role_id' => Roles::ADMIN->value,
        'status' => Status::ACTIVE->value,
        'password' => '12345678',
    ];

    expect(CreateUser::execute($params))->toBeFalse();
});
