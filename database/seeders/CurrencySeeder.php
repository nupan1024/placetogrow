<?php

namespace Database\Seeders;

use App\Support\Definitions\CurrenciesTypes;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('currencies')->insert(CurrenciesTypes::toArray());

    }
}
