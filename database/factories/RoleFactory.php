<?php

namespace Database\Factories;

use App\Support\Definitions\Roles;
use Illuminate\Database\Eloquent\Factories\Factory;
use Spatie\Permission\Models\Role;

/**
 * @extends Factory<Role>
 */
class RoleFactory extends Factory
{
    protected $model = Role::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => collect(Roles::toArray())->pluck('name')->flatten()->random(),
            'guard_name' => 'web',
        ];
    }
}
