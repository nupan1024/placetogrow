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
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            CurrencySeeder::class,
            CategorySeeder::class,
            MicrositeTypeSeeder::class,
        ]);

        if (env('APP_ENV') !== 'production') {
            $this->call(MicrositeSeeder::class);
        }

    }
}
