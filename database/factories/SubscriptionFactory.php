<?php

namespace Database\Factories;

use App\Domain\Currencies\Models\Currency;
use App\Domain\Microsites\Models\Microsite;
use App\Domain\Subscriptions\Models\Subscription;
use App\Support\Definitions\BillingFrequency;
use App\Support\Definitions\Status;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Subscription>
 */
class SubscriptionFactory extends Factory
{
    protected $model = Subscription::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'microsite_id' => Microsite::factory(),
            'name' => fake()->name(),
            'amount' => fake()->numberBetween(1000, 99999),
            'description' => fake()->paragraph(),
            'currency_id' => Currency::factory(),
            'time_expire' => fake()->dateTimeBetween('now', '+1 year')->format('Y-m-d'),
            'billing_frequency' => BillingFrequency::MONTH->value,
            'status' => Status::ACTIVE->value,
        ];
    }
}
