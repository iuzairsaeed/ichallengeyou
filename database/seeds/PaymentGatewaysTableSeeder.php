<?php

use Illuminate\Database\Seeder;
use App\Models\PaymentGateway;

class PaymentGatewaysTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PaymentGateway::insert([
            [
                'name' => 'PAYPAL',
                'created_at' => now()
            ],
            [
                'name' => 'BITCOIN',
                'created_at' => now()
            ]
        ]);
    }
}
