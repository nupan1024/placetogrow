<?php

use App\Domain\Invoices\Actions\GetInvoiceByCode;
use App\Domain\Invoices\Models\Invoice;

test('Get invoice by code', function () {
    $code = 'MICROSITE_PLACETOGROW_'.time();
    Invoice::factory()->create([
        'code' => $code
    ]);

    expect(GetInvoiceByCode::execute(['code' => $code,
    ]))->toBeInstanceOf(Invoice::class);
});
