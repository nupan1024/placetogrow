<?php

namespace Database\Factories;

use \App\Domain\Microsites\Models\MicrositeType;
use App\Support\Definitions\Status;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MicrositeType>
 */
class MicrositeTypeFactory extends Factory
{

    protected $model = MicrositeType::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->currencyCode(),
            'status' => Status::ACTIVE->value
        ];
    }
}
