<?php

namespace Database\Factories;

use App\Domain\Imports\Models\Import;
use App\Domain\Users\Models\User;
use App\Support\Definitions\ImportStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Import>
 */
class ImportsFactory extends Factory
{
    protected $model = Import::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'path' => 'file.csv',
            'file_name' => 'file.csv',
            'status' => fake()->randomElement(ImportStatus::toArray()),
            'errors' => [],
            'user_id' => User::factory(),
        ];
    }
}
