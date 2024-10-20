<?php

use App\Domain\Invoices\Models\Invoice;
use App\Domain\Microsites\Models\Microsite;
use App\Domain\Microsites\Models\MicrositeType;
use App\Jobs\ProcessInvoices;
use App\Support\Definitions\MicrositesTypes;
use App\Support\Definitions\StatusInvoices;

test('job can process update status invoices', function () {
    MicrositeType::factory()->create([
        'id' => MicrositesTypes::INVOICE->value,
    ]);

    $microsite = Microsite::factory()->create([
        'microsites_type_id' => MicrositesTypes::INVOICE->value,
    ]);
    Invoice::factory()->create([
        'date_expire_pay' => now()->subDays(5),
        'status' => StatusInvoices::PENDING->name,
        'microsite_id' => $microsite->id,
    ]);

    $job = new ProcessInvoices();
    $job->handle();


    $this->assertDatabaseHas('invoices', [
        'status' => StatusInvoices::EXPIRED->name,
    ]);
});
