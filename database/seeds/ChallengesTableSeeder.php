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

        for ($i = 0; $i < 25; $i++) {
            Challenge::create([
                'user_id' => $faker->randomElement([1,2]),
                'title' => $faker->unique()->word . ' ' . $faker->unique()->word,
                'description' => $faker->paragraph(),
                'start_time' => now(),
                'file' => $faker->imageUrl,
                'location' => $faker->country,
                'amount' => $faker->randomNumber(2),
                'duration_minutes' => $faker->numberBetween(300, 900),
                'is_approved' => $faker->boolean(50),
            ]);
        }
    }
}
