<?php

namespace Database\Seeders;

use App\Domain\Categories\Models\Category;
use App\Domain\Users\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RolSeeder::class);
//        User::factory()->create([
//            'name' => 'Test User',
//            'email' => 'test@example.com',
//        ]);

        if (env('APP_ENV') !== 'production') {
            $this->call([CurrencySeeder::class, CategorySeeder::class, MicrositeTypeSeeder::class]);
        }
    }
}
