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
        $invoice['data'] = btcInvoice($request);
        $data = BitcoinCollection::collection($invoice);
        $transaction = [
            "user_id" => auth()->id(),
            'amount' => $request->price,
            'type' => 'load',
            'invoice_id' => $invoice['data']['data']['id'],
            'invoice_type' => "BITCOIN",
            'created_at' => now(),
        ];
        $this->model->create($transaction);
        return response($data,200);
    }

    public function confirm()
    {
        
    }


}
