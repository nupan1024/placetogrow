<?php

namespace Database\Seeders;

use App\Domain\Invoices\Models\Invoice;
use App\Domain\Microsites\Models\Microsite;
use App\Domain\Users\Models\User;
use App\Support\Definitions\Roles;
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
        Invoice::factory()->count(3)->create([
            'microsite_id' => $micrositeFactory->invoiceType()->create(),
            'user_id' => User::factory()->create([
                'role_id' => Roles::GUEST->value
            ])
        ]);
    }
}
