<?php

use App\Domain\Invoices\Actions\DeleteInvoice;
use App\Domain\Invoices\Models\Invoice;
use App\Support\Definitions\StatusInvoices;

test('Delete invoice', function () {
    $invoice = Invoice::factory()->create();

    expect(DeleteInvoice::execute([
        'model' => $invoice,
        'status' => StatusInvoices::PAID->name
    ]))->toBeTrue();
});

test('Delete invoice with status different than pending', function () {
    $invoice = Invoice::factory()->create([
        'status' => StatusInvoices::PAID->name
    ]);

    expect(DeleteInvoice::execute(['model' => $invoice]))->toBeFalse();
});

test('Does not receive invoice model', function () {

    expect(DeleteInvoice::execute(['model' => null]))->toBeFalse();
});
