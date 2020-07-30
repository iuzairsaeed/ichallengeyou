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
        $user = auth()->user();
        (float)$amount = $request->price;
        $invoice['data'] = btcInvoice($amount);
        if(!$user->is_premium){
            $transaction = [
                "user_id" => auth()->id(),
                'amount' => config('global.PREMIUM_COST'),
                'type' => 'miscellaneous',
                'invoice_id' => $invoice['data']['data']['id'],
                'invoice_type' => "BITCOIN",
                'status' => "pending",
                'created_at' => now(),
            ];
            $this->model->create($transaction);
            $amount = $amount - config('global.PREMIUM_COST');
        }
        $transaction = [
            "user_id" => auth()->id(),
            'amount' => $amount,
            'type' => 'load',
            'invoice_id' => $invoice['data']['data']['id'],
            'invoice_type' => "BITCOIN",
            'status' => "pending",
            'created_at' => now(),
        ];
        $this->model->create($transaction);
        $data = BitcoinCollection::collection($invoice);
       
        return response($data,200);
    }

    public function confirm(Request $request)
    {
        try {
            $user = auth()->user();
            $invoice_id = $request->invoice_id;
            (float)$amount= Transaction::where('invoice_id' , $invoice_id)->sum('amount'); 
            $transaction = Transaction::where('invoice_id' , $invoice_id)->where('status' , 'paid')->first();
            if($transaction){
                return response(['message'=>'You have already loaded your balance'] , 402 ); 
            }
            $btc_info = btcInfo($request);
            if($btc_info['data']['status'] == 'paid' || $btc_info['data']['status'] == 'confirmed'){
                $data = $this->createTransaction($invoice_id,$amount);
                return response($data,200);
            }
            return response(['message'=>'Kindly pay from your wallet.']);
        } catch (\Throwable $th) {
            return response(['message'=>'Kindly pay from your wallet.']);
        }
        
    }

    public function createTransaction($invoice_id,$amount)
    {
        $user = auth()->user();
        $transaction = Transaction::where('invoice_id' , $invoice_id)->first();
        if(!$user->is_premium){
            $user->is_premium = true;
            $transaction = Transaction::where('invoice_id' , $invoice_id)
            ->where('type' , 'miscellaneous')
            ->first();
            $transaction->status = 'paid';
            $transaction->update();      
            $amount = $amount - config('global.PREMIUM_COST');      
        }
        
        $user->balance = (float)$user->getAttributes()['balance'] + $amount;
        $user->update();

        $transaction = Transaction::where('invoice_id' , $invoice_id)
        ->where('type' , 'load')
        ->first();
        $transaction->status = 'paid';
        $transaction->update();
        $data = [
            'message' => config('global.CURRENCY').' '.number_format($amount,2).' has been credited to your account & Your Total Amount is '.$user->balance,
            'amount' => $user->balance,
            'is_premium' => $user->is_premium,
        ];
        return $data;
    }
}
