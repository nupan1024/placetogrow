<?php

use App\Domain\Invoices\Models\Invoice;
use App\Domain\Microsites\Models\Microsite;
use App\Domain\Payments\Actions\CreatePayment;
use App\Support\Definitions\DocumentsTypes;

test('create payment', function () {
    $invoice = Invoice::factory()->create();
    $microsite = Microsite::factory()->create();
    $params = [
        'name' => 'User test',
        'email' => 'test@test.com',
        'value' => '60000',
        'invoice_id' => $invoice->id,
        'type_document' => DocumentsTypes::CC->value,
        'num_document' => '123455',
        'microsite_id' => $microsite->id,
    ];

    expect(CreatePayment::execute($params))->toBeObject();
});

test('Does not receive all parameters', function () {
    $invoice = Invoice::factory()->create();
    $params = [
        'name' => 'User test',
        'email' => 'test@test.com',
        'value' => '60000',
        'invoice_id' => $invoice->id,
        'type_document' => DocumentsTypes::CC->value,
        'num_document' => '123455',
    ];

    expect(CreatePayment::execute($params))->toBeFalse();
});
