<?php

namespace Database\Seeders;

use App\Domain\Invoices\Models\Invoice;
use App\Domain\Microsites\Models\Microsite;
use Illuminate\Database\Seeder;

class InvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /**
         * @var \Database\Factories\MicrositeFactory $micrositeFactory
         */
        $micrositeFactory = Microsite::factory();
        Invoice::factory()->count(3)->create(['microsite_id' => $micrositeFactory->invoiceType()->create()]);
    }
}
