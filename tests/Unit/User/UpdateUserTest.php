<?php

use App\Domain\Users\Actions\UpdateUser;
use App\Domain\Users\Models\User;

test('update user', function () {
    $user = User::factory()->create();
    $params['id'] = $user->id;
    $params['fields'] = [
        'name' => 'User test',
        'email' => 'usertest@placetogrow.com',
        'role_id' => $user->role_id,
        'status' => $user->status,
    ];

    expect(UpdateUser::execute($params))->toBeTrue();
});

test('generate exception', function () {
    $user = User::factory()->create();

    $params['fields'] = [
        'name' => 'User test',
        'email' => 'usertest@placetogrow.com',
        'role_id' => $user->role_id,
        'status' => $user->status,
    ];

    expect(UpdateUser::execute($params))->toBeFalse();
});
