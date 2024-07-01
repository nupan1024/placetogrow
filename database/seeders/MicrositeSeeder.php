<?php

namespace Database\Seeders;

use App\Domain\Categories\Models\Category;
use App\Domain\Currencies\Models\Currency;
use App\Domain\Microsites\Models\Microsite;
use App\Domain\Microsites\Models\MicrositeType;
use Illuminate\Database\Seeder;

class MicrositeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Microsite::factory()
            ->count(5)
            ->state(fn (array $attributes) => [
                'microsites_type_id' => MicrositeType::all()->random(),
                'category_id' => Category::all()->random(),
                'currency_id' => Currency::all()->random(),
                'date_expire_pay' => date('Y-m-d'),
            ])->create();
    }
}
