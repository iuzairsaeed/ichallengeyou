<?php

use Illuminate\Database\Seeder;
use App\Models\SubmitChallenge;

class SubmitChallengeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SubmitChallenge::create([
            'accepted_challenge_id' => 1,
        ]);
        SubmitChallenge::create([
            'accepted_challenge_id' => 2,
        ]);

    }
}
