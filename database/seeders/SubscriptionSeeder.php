<?php

namespace Database\Seeders;

use App\Domain\Currencies\Models\Currency;
use App\Domain\Microsites\Models\Microsite;
use App\Domain\Subscriptions\Models\Subscription;
use Illuminate\Database\Seeder;

class SubscriptionSeeder extends Seeder
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
        Subscription::factory()->count(3)->create([
            'microsite_id' => $micrositeFactory->subscriptionType()->create(),
            'currency_id' => Currency::all()->random(),
        ]);
    }
}
