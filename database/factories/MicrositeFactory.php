<?php

namespace Database\Factories;

use App\Domain\Categories\Models\Category;
use App\Domain\Currencies\Models\Currency;
use App\Domain\Microsites\Models\Microsite;
use App\Domain\Microsites\Models\MicrositeType;
use App\Support\Definitions\Status;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Microsite>
 */
class MicrositeFactory extends Factory
{
    protected $model = Microsite::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'microsite_type_id' => fake()->randomElement(MicrositeType::all())['id'],
            'category_id' => fake()->randomElement(Category::all())['id'],
            'name' => fake()->name,
            'logo_path' => '',
            'currency_id' => fake()->randomElement(Currency::all())['id'],
            'time_expire_pay' => 900,
            'status' => Status::ACTIVE->value
        ];
    }
}
