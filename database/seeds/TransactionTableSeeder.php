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
            'invoice_id' => 'Random-Number',
        ]);
        $transaction->save();
        $transaction = new Transaction([
            'user_id' => 1,
            'amount' => 99,
            'type' => 'load',
            'invoice_id' => 'Random-Number',
        ]);
        $transaction->save();
        $transaction = new Transaction([
            'user_id' => 1,
            'challenge_id' => 1,
            'amount' => 1,
            'type' => 'create_challenge',
            'invoice_id' => 'Random-Number',
        ]);
        $transaction->save();
        $transaction = new Transaction([
            'user_id' => 2,
            'challenge_id' => 1,
            'amount' => 50,
            'type' => 'donate',
            'invoice_id' => 'Random-Number',
        ]);
        $transaction->save();
        # ================================
        $transaction = new Transaction([
            'user_id' => 2,
            'challenge_id' => 1,
            'amount' => 150,
            'type' => 'won_challenge',
            'invoice_id' => 'Random-Number',
        ]);
        $transaction->save();
        $transaction = new Transaction([
            'user_id' => 2,
            'challenge_id' => 1,
            'amount' => 150,
            'type' => 'won_challenge',
            'invoice_id' => 'withdraw',
        ]);
        $transaction->save();
        
    }
}
