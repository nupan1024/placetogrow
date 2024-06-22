<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([RolSeeder::class, UserSeeder::class]);

        if (env('APP_ENV') !== 'production') {
            $this->call([CurrencySeeder::class, CategorySeeder::class, MicrositeTypeSeeder::class]);
        }
    }
}
