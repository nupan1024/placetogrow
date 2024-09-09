<?php

use App\Domain\Payments\Actions\ListPaymentsByUser;
use App\Domain\Users\Models\User;

test('list payments by user', function () {
    $user = User::factory()->create();

    $params = [
        'page' => 1,
        'filter' => "",
        'user_id' => $user->id

    ];
    expect(ListPaymentsByUser::execute($params))->toBeObject();
});

test('get payments by user with filter', function () {
    $user = User::factory()->create();

    $params = [
        'page' => 1,
        'filter' => "Test",
        'user_id' => $user->id
    ];
    expect(ListPaymentsByUser::execute($params))->toBeObject();
});
