<?php

namespace Database\Factories;

use App\Domain\Fields\Models\Field;
use App\Support\Definitions\FieldsAttributes;
use App\Support\Definitions\FieldsTypes;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Field>
 */
class FieldFactory extends Factory
{
    protected $model = Field::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => Str::snake(fake()->words(3, true)),
            'type' => FieldsTypes::TEXT_INPUT->value,
            'attributes' => [FieldsAttributes::REQUIRED->value],
            'label' => Str::limit(fake()->unique()->name(), 10)
        ];
    }
}
