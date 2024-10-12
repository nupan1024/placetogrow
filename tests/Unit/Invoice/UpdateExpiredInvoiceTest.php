<?php

use App\Domain\Invoices\Actions\UpdateExpiredInvoice;
use App\Domain\Invoices\Models\Invoice;
use App\Support\Definitions\StatusInvoices;

test('Update Expired Invoice', function () {
    $invoice = Invoice::factory()->create();

    expect(
        UpdateExpiredInvoice::execute(
            [
                'status' => StatusInvoices::PAID->name,
                'value' => fake()->numberBetween(100, 1000),
            ],
            $invoice
        )
    )->toBeTrue();
});

test('Does not receive every parameters', function () {
    $invoice = Invoice::factory()->create();

    expect(UpdateExpiredInvoice::execute([], $invoice))->toBeFalse();
});
