<?php

use App\Domain\Invoices\Models\Invoice;
use App\Domain\Microsites\Models\Microsite;
use App\Jobs\UpdateStatusInvoices;
use App\Support\Definitions\StatusInvoices;

test('job can process update status invoices', function () {
    $microsite = Microsite::factory()->create([
        'date_expire_pay' => now()->subDays(5),
    ]);
    Invoice::factory()->create([
        'status' => StatusInvoices::PENDING->name,
        'microsite_id' => $microsite->id,
    ]);

    $job = new UpdateStatusInvoices();
    $job->handle();


    $this->assertDatabaseHas('invoices', [
        'status' => StatusInvoices::EXPIRED->name,
    ]);
});
