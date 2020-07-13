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
                'user_id' => $faker->randomElement([1,2,3,4]),
                'notifiable_id' => $faker->randomElement([1,2]),
                'notifiable_type' => $faker->randomElement(['App\Models\SubmitChallenge','App\Models\Vote']),
                'title' => 'Dummy Heading',
                'body' => 'Dummy Text!',
            ]);
        }
    }
}
