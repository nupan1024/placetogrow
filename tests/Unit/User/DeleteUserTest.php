<?php

use App\Domain\Users\Actions\DeleteUser;
use App\Domain\Users\Models\Role;
use App\Domain\Users\Models\User;
use App\Support\Definitions\Roles;
use App\Support\Definitions\Status;
use Illuminate\Support\Facades\Hash;

test('delete user', function () {
    $role = Role::factory()->create([
        'id' => Roles::ADMIN->value,
        'name' => Roles::ADMIN->name,
        'guard_name' => 'web',
    ]);

    $user = User::factory()->create([
        'name' => 'User test',
        'email' => 'usertest@placetogrow.com',
        'role_id' => $role->id,
        'status' => Status::ACTIVE->value,
        'password' => Hash::make('password'),
    ]);

    expect(DeleteUser::execute(['id' => $user->id]))->toBeTrue();
});

test('not found id user', function () {

    expect(DeleteUser::execute(['id' => '5']))->toBeFalse();
});
