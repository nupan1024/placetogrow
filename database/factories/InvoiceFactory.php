<?php

namespace Database\Factories;

use App\Domain\Microsites\Models\Microsite;
use App\Domain\Users\Models\User;
use Dnetix\Redirection\Entities\Status;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Domain\Invoices\Models\Invoice;

/**
 * @extends Factory<Invoice>
 */
class InvoiceFactory extends Factory
{
    protected $model = Invoice::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'microsite_id' => Microsite::factory(),
            'user_id' => User::factory(),
            'value' => fake()->numerify('2000'),
            'description' => fake()->paragraph(),
            'status' => Status::ST_PENDING,
            'code' => 'microsite_placetopay'.fake()->randomNumber(6),
            'date_expire_pay' => fake()->dateTimeBetween('now', '+1 year')->format('Y-m-d'),
        ];
    }
}
