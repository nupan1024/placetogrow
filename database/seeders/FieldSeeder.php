<?php

namespace Database\Seeders;

use App\Domain\Fields\Models\Field;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class FieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Field::factory()
            ->count(3)
            ->state(new Sequence(
                ['label' => 'Celular'],
                ['label' => 'Direccion'],
                ['label' => 'Nacionalidad'],
            ))->create();
    }
}
