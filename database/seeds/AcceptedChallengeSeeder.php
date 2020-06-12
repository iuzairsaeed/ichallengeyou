<?php

use Illuminate\Database\Seeder;
use App\Models\AcceptedChallenge;

class AcceptedChallengeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AcceptedChallenge::create([
            'user_id' => 2,
            'challenge_id' => 3,
            'created_at' => now()
        ]);
        AcceptedChallenge::create([
            'user_id' => 3,
            'challenge_id' => 3,
            'created_at' => now()
        ]);
    }
}
