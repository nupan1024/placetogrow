<?php

namespace Database\Seeders;

use App\Domain\Categories\Models\Category;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::factory()
            ->count(4)
            ->state(new Sequence(
                ['name' => 'General'],
                ['name' => 'Salud'],
                ['name' => 'Economía'],
                ['name' => 'Noticias']
            ))->create();
    }
}
