<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(CategoriesTableSeeder::class);
        $this->call(ChallengesTableSeeder::class);
        $this->call(SettingsTableSeeder::class);
        $this->call(TransactionTableSeeder::class);
        // $this->call(AcceptedChallengeSeeder::class);
        // $this->call(NotificationTableSeeder::class);
        $this->call(BidTableSeeder::class);
        // $this->call(SubmitChallengeTableSeeder::class);
        // $this->call(VoteChallengeTableSeeder::class);
    }
}
