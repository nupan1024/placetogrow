<?php

use App\Domain\Invoices\Actions\ListInvoicesByUser;
use App\Domain\Users\Models\User;

test('list invoices by user', function () {
    $user = User::factory()->create();
    $params = [
        'page' => 1,
        'filter' => "",
        'user_id' => $user->id
    ];
    expect(ListInvoicesByUser::execute($params))->toBeObject();
});

test('get invoices by user with filter', function () {
    $user = User::factory()->create();

    $params = [
        'page' => 1,
        'filter' => "Test",
        'user_id' => $user->id
    ];
    expect(ListInvoicesByUser::execute($params))->toBeObject();
});
