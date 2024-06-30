<?php

namespace Database\Seeders;

use App\Domain\Currencies\Models\Currency;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Currency::factory()
            ->count(2)
            ->state(new Sequence(
                ['name' => 'COP'],
                ['name' => 'USD']
            ))->create();
    }
}
