<?php

use Illuminate\Database\Seeder;
use App\Models\Vote;

class VoteChallengeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Vote::create([
            'submited_challenge_id' => 1,
            'vote_up' => true,
        ]);
        Vote::create([
            'submited_challenge_id' => 1,
            'vote_up' => true,
        ]);
    }
}
