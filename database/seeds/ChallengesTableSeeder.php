<?php

use Illuminate\Database\Seeder;
use App\Models\Challenge;

class ChallengesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        for ($i = 0; $i < 15; $i++) {
            Challenge::create([
                'user_id' => $faker->randomElement([1,2]),
                'title' => $faker->unique()->word . ' ' . $faker->unique()->word,
                'description' => $faker->paragraph(),
                'start_time' => now(),
                'file' => $faker->imageUrl,
                'location' => $faker->country,
                'amount' => $faker->randomNumber(2),
                'duration_days' => $faker->numberBetween(0, 10),
                'duration_hours' => $faker->numberBetween(0, 24),
                'duration_minutes' => $faker->numberBetween(0, 60),
                'is_approved' => $faker->boolean(50),
            ]);
        }
    }
}
