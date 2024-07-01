<?php

namespace Database\Seeders;

use App\Support\Definitions\MicrositesTypes;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MicrositeTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('microsites_types')->insert(MicrositesTypes::toArray());
    }
}
