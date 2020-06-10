<?php

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Setting::insert([
            [
                'name' => 'CURRENCY',
                'value' => '$',
                'created_at' => now()
            ]
        ]);
        Setting::insert([
            [
                'name' => 'PREMIUM_COST',
                'value' => 1,
                'created_at' => now()
            ]
        ]);
    }
}
