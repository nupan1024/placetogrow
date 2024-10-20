<?php

namespace Database\Seeders;

use App\Domain\Settings\Models\Setting;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::factory()->count(2)
            ->state(new Sequence(
                ['key' => 'attempts', 'value' => 3],
                ['key' => 'period_time', 'value' => 1440],
            ))->create();
    }
}
