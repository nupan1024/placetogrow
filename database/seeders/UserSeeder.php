<?php

namespace Database\Seeders;

use App\Domain\Users\Models\User;
use App\Support\Definitions\Roles;
use App\Support\Definitions\Status;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@placetogrow.com',
            'role_id' => Roles::SUPER_ADMIN->value,
            'status' => Status::ACTIVE->value,
            'email_verified_at' => now(),
            'password' => bcrypt('12345678'),
            'remember_token' => Str::random(10),
        ])->assignRole('Super Admin');
    }
}
