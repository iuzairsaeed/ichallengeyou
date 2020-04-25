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
        factory(Challenge::class, 10)->create();
    }
}
