<?php

use Illuminate\Database\Seeder;
use App\Models\Vote;

class VoteTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        # VOTE UP
        for ($i=0; $i <= 10; $i++) { 
        Vote::create([
            'user_id' => $faker->randomElement([4,5]),
            'submited_challenge_id' => $faker->randomElement([1,2]),
            'vote_up' => true,
            ]);
        }
        # VOTE DOWN   
        for ($i=0; $i <= 7; $i++) { 
            Vote::create([
                'user_id' => $faker->randomElement([4,5]),
                'submited_challenge_id' => $faker->randomElement([1,2]),
                'vote_down' => true,
            ]);
        }
    }
}
