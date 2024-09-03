<?php

use App\Domain\Invoices\Actions\GetInvoicesByMicrositeAndUser;
use App\Domain\Invoices\Models\Invoice;
use App\Domain\Microsites\Models\Microsite;

test('Get invoice by microsite and user', function () {
    Invoice::factory()->create();
    $microsite = Microsite::factory()->create();
    expect(GetInvoicesByMicrositeAndUser::execute([
        'microsite_id' => $microsite->id,
        'user_id' => auth()->user()->id ?? ""
    ]))->toBeArray();
});
