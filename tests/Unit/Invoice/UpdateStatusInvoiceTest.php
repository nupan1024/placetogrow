<?php

use App\Domain\Invoices\Actions\UpdateStatusInvoice;
use App\Domain\Invoices\Models\Invoice;
use App\Support\Definitions\StatusInvoices;

test('Update status invoice', function () {
    $invoice = Invoice::factory()->create();

    expect(
        UpdateStatusInvoice::execute(
            ['status' => StatusInvoices::PAID->name],
            $invoice
        )
    )->toBeTrue();
});

test('Does not receive status', function () {
    $invoice = Invoice::factory()->create();

    expect(UpdateStatusInvoice::execute([], $invoice))->toBeFalse();
});
