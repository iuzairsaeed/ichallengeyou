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
            'invoice_id' => "PAYPAL1-1234567893216540",
            'invoice_type' => 'PAYPAL',
            'amount' => 1,
            'type' => 'miscellaneous',
        ]);
        $transaction->save();
        $transaction = new Transaction([
            'user_id' => 1,
            'amount' => 99,
            'type' => 'load',
            'invoice_id' => "PAYPAL1-1234567893216540",
            'invoice_type' => 'PAYPAL',
        ]);
        $transaction->save();
        $transaction = new Transaction([
            'user_id' => 1,
            'challenge_id' => 2,
            'amount' => 1,
            'type' => 'create_challenge',
            'invoice_id' => "BITCOIN1-1234567893216540",
            'invoice_type' => 'BITCOIN',
        ]);
        $transaction->save();
        $transaction = new Transaction([
            'user_id' => 2,
            'challenge_id' => 2,
            'amount' => 50,
            'type' => 'donate',
            'invoice_id' => "BITCOIN1-1234567893216540",
            'invoice_type' => 'BITCOIN',
        ]);
        $transaction->save();
        $transaction = new Transaction([
            'user_id' => 2,
            'challenge_id' => 2,
            'amount' => 150,
            'type' => 'won_challenge',
        ]);
        $transaction->save();
        $transaction = new Transaction([
            'user_id' => 2,
            'amount' => 150,
            'type' => 'withdraw',
        ]);
        $transaction->save();

    }
}
