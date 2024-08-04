<?php

namespace Database\Seeders;

use App\Domain\Invoices\Models\Invoice;
use Illuminate\Database\Seeder;

class InvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Invoice::factory()->count(3)->create();
    }
}