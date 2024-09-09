<?php

use App\Domain\Microsites\Actions\ListInvoicesByMicrosite;
use App\Domain\Microsites\Models\Microsite;

test('get invoices by microsite', function () {
    $params = [
        'page' => 1,
        'filter' => ""
    ];
    $microsite = Microsite::factory()->create();
    expect(ListInvoicesByMicrosite::execute($params, $microsite))->toBeObject();
});

test('get invoices by microsite with filter', function () {
    $params = [
        'page' => 1,
        'filter' => "Test"
    ];
    $microsite = Microsite::factory()->create();
    expect(ListInvoicesByMicrosite::execute($params, $microsite))->toBeObject();
});
