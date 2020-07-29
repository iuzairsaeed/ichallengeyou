<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\ChallengeRepository;
use App\Http\Resources\BitcoinCollection;
use App\Models\Transaction;
use Illuminate\Http\Request;

class BitcoinController extends Controller
{

    protected $model;

    public function __construct(Transaction $model) {
        $this->model = new ChallengeRepository($model);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function invoice(Request $request)
    {
        $invoice = btcInvoice($request->price);            
        $data = BitcoinCollection::collection($invoice);
        $transaction = [
            "user_id" => auth()->id(),
            'amount' => $request->price,
            'type' => 'load',
            'invoice_id' => $invoice['data']['data']['id'],
            'invoice_type' => "BITCOIN",
            'status' => "pending",
            'created_at' => now(),
        ];
        $this->model->create($transaction);
        return response($data,200);
    }

    public function confirm(Request $request)
    {
        $user = auth()->user();
        (float)$amount = $request->price; 
        $invoice_id = $request->invoice_id;
        $transaction = Transaction::where('invoice_id' , $invoice_id)
        ->where('status' , 'paid')
        ->first();
        if($transaction){
            return response(['message'=>'You have already loaded your balance'] , 402 ); 
        }
        $btc_info = btcInfo($request);
        if($btc_info['data']['status'] == 'paid' || $btc_info['data']['status'] == 'confirmed'){
            $this->createTransaction($invoice_id,$amount);
        }
        $data = [
            'message' => config('global.CURRENCY').' '.$amount.' has been credited to your account & Your Total Amount is '.$user->balance,
            'amount' => $user->balance,
            'is_premium' => $user->is_premium,
        ];
        return response($data,200);
    }

    public function createTransaction($invoice_id,$amount)
    {
        $user = auth()->user();
        if(!$user->is_premium){
            $user->is_premium = true;
            $transaction = new Transaction([
                'user_id' => auth()->id(),
                'challenge_id' => null,
                'amount' => config('global.PREMIUM_COST'),
                'type' => 'miscellaneous',
                'invoice_id' => $invoice_id,
                'invoice_type' => 'BITCOIN',
                'status' => 'paid',
            ]);
            $user->transactions()->save($transaction);
            $amount = $amount - config('global.PREMIUM_COST');
        }
        
        $user->balance = (float)$user->getAttributes()['balance'] + $amount;
        $user->update();

        $transaction = new Transaction([
            'user_id' => auth()->id(),
            'challenge_id' => null,
            'amount' => $amount,
            'type' => 'load',
            'invoice_id' => $invoice_id,
            'invoice_type' => 'BITCOIN',
            'status' => 'paid',
        ]);
        $user->transactions()->save($transaction);
        
    }
}
