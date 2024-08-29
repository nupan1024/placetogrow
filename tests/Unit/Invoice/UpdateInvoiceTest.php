<?php

use App\Domain\Invoices\Actions\UpdateInvoice;
use App\Domain\Invoices\Models\Invoice;

test('update invoice', function () {
    $invoice = Invoice::factory()->create();
    $params = [
        'microsite_id' => $invoice->microsite_id,
        'user_id' => $invoice->user_id,
        'value' => '6000',
        'description' => fake()->paragraph(),
    ];

    expect(UpdateInvoice::execute([
        'model' => $invoice,
        'data' => $params
    ]))->toBeTrue();
});

test('Does not receive invoice model', function () {
    $invoice = Invoice::factory()->create();
    $params = [
        'value' => '6000',
        'description' => fake()->paragraph(),
    ];

    expect(UpdateInvoice::execute([
        'model' => $invoice,
        'data' => $params
    ]))->toBeFalse();
});
