<?php

use App\Domain\Invoices\Actions\ListInvoices;

test('list invoices', function () {
    $params = [
        'page' => 1,
        'filter' => ""
    ];
    expect(ListInvoices::execute($params))->toBeObject();
});

test('get invoices with filter', function () {
    $params = [
        'page' => 1,
        'filter' => "Test"
    ];
    expect(ListInvoices::execute($params))->toBeObject();
});
