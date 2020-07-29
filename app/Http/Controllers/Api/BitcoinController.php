<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\ChallengeRepository;
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

    public function invoice()
    {
        $invoice = btcInvoice();
        return $invoice;
    }

}
