<?php

use App\Domain\Invoices\Actions\UpdateInvoice;
use App\Domain\Invoices\Models\Invoice;
use Carbon\Carbon;

test('update invoice', function () {
    $invoice = Invoice::factory()->create();
    $params = [
        'microsite_id' => $invoice->microsite_id,
        'user_id' => $invoice->user_id,
        'value' => '6000',
        'description' => fake()->paragraph(),
        'date_expire_pay' => fake()->date(Carbon::now()->addDays(3)->format('Y-m-d')),
    ];

    expect(UpdateInvoice::execute($params, $invoice))->toBeTrue();
});

test('Does not receive parameters required', function () {
    $invoice = Invoice::factory()->create();
    $params = [
        'value' => '6000',
        'description' => fake()->paragraph(),
    ];

    expect(UpdateInvoice::execute($params, $invoice))->toBeFalse();
});
