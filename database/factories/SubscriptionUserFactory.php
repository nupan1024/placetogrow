<?php

namespace Database\Factories;

use App\Domain\Subscriptions\Models\Subscription;
use App\Domain\Users\Models\User;
use App\Support\Definitions\StatusInvoices;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Domain\SubscriptionUser\Models\SubscriptionUser;

/**
 * @extends Factory<SubscriptionUser>
 */
class SubscriptionUserFactory extends Factory
{
    protected $model = SubscriptionUser::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'subscription_id' => Subscription::factory(),
            'status' => StatusInvoices::PENDING->name,
        ];
    }
}
