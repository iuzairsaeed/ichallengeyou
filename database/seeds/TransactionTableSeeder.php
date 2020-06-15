<?php

use Illuminate\Database\Seeder;
use App\Models\Transaction;

class TransactionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $transaction = new Transaction([
            'user_id' => 1,
            'amount' => 1,
            'type' => 'miscellaneous',
        ]);
        $transaction->save();
        $transaction = new Transaction([
            'user_id' => 1,
            'amount' => 99,
            'type' => 'load',
        ]);
        $transaction->save();
        $transaction = new Transaction([
            'user_id' => 1,
            'challenge_id' => 1,
            'amount' => 1,
            'type' => 'create_challenge',
        ]);
        $transaction->save();
        $transaction = new Transaction([
            'user_id' => 2,
            'challenge_id' => 1,
            'amount' => 50,
            'type' => 'donate',
        ]);
        $transaction->save();
        $transaction = new Transaction([
            'user_id' => 2,
            'challenge_id' => 1,
            'amount' => 150,
            'type' => 'won_challenge',
        ]);
        $transaction->save();
        $transaction = new Transaction([
            'user_id' => 2,
            'challenge_id' => 1,
            'amount' => 150,
            'type' => 'withdraw',
        ]);
        $transaction->save();

    }
}
