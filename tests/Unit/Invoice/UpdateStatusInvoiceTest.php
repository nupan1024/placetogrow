<?php

use App\Domain\Invoices\Actions\UpdateStatusInvoice;
use App\Domain\Invoices\Models\Invoice;
use App\Support\Definitions\StatusInvoices;

test('Update status invoice', function () {
    $invoice = Invoice::factory()->create();

    expect(UpdateStatusInvoice::execute([
        'model' => $invoice,
        'status' => StatusInvoices::PAID->name
    ]))->toBeTrue();
});

test('Does not receive status', function () {
    $invoice = Invoice::factory()->create();

    expect(UpdateStatusInvoice::execute([
        'model' => $invoice,
    ]))->toBeFalse();
});
