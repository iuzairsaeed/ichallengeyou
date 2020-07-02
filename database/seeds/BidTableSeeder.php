<?php

use Illuminate\Database\Seeder;
use App\Models\Bid;

class BidTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $bid = [
            'user_id' => 2,
            'challenge_id' => 1,
            'bid_amount' => 100,
        ];
        Bid::create($bid);
    }
}
