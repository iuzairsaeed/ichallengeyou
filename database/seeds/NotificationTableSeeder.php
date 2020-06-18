<?php

use Illuminate\Database\Seeder;
use App\Models\Notification;

class NotificationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        for ($i = 0; $i <= 60; $i++) {
            Notification::create([
                'challenge_id' => $faker->randomElement([1,2,3,4]),
                'user_id' => $faker->randomElement([1,2,3,4]),
                'title' => 'Dummy Heading',
                'body' => 'Dummy Text!',
            ]);
        }
    }
}
