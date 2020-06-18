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
        $number = 0;
        $faker = \Faker\Factory::create();
        for ($i = 0; $i <= 1; $i++) {
            $number = $number + 2;
            for ($i = 0; $i <= 12; $i++) {
                Notification::create([
                    'challenge_id' => $faker->randomElement([1,2,3,4]),
                    'user_id' => $number,
                    'title' => 'Challenge Submited', 
                    'body' => 'Some User has been Submited the Challenge!', 
                ]);
            }
        }
    }
}
