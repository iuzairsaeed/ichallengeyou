<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Bids\BidRequest;
use App\Http\Resources\BidCollection;
use App\Repositories\ChallengeRepository;
use App\Models\Challenge;
use App\Models\User;
use App\Models\Bid;

class BidController extends Controller
{

    protected $model;

    public function __construct(Bid $model) {
        $this->model = new ChallengeRepository($model);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Challenge $challenge, Request $request)
    {
        $orderableCols = [];
        $searchableCols = [];
        $whereChecks = ['challenge_id'];
        $whereOps = ['='];
        $whereVals = [$challenge->id];
        $with = ['user'];
        $withCount = [];
        $currentStatus = [];
        $withSums = [];
        $withSumsCol = [];
        $addWithSums = [];
        $whereHas = null;
        $withTrash = false;

        $data = $this->model->getData($request, $with, $withTrash, $withCount, $whereHas, $withSums, $withSumsCol, $addWithSums, $whereChecks,
                                        $whereOps, $whereVals, $searchableCols, $orderableCols, $currentStatus);
        collect($data['data'])->map(function ($item) {
            $item['bid_amount'] = config('global.CURRENCY').' '.$item->bid_amount;
        });
        $data['data'] = BidCollection::collection($data['data']);
        return response($data, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Challenge $challenge,BidRequest $request)
    {
        $message['message'] = 'It\'s 1 USD for god sake. Don’t be so cheap!';
        $message['premiumBtn'] = true;
        if(auth()->user()->is_premium){
            $message['premiumBtn'] = false;
            $message['message'] = 'You Can\'t Bid on This Challenge!';
            if($challenge->user_id != auth()->id()) {
                $message['message'] = 'You have already Bid on This Challenge!';
                if(!$challenge->bids()->where('user_id',auth()->id())->exists()){
                    $message['message'] = 'You are out of time!';
                    $after_date = $challenge->after_date;
                    if(now() <= $after_date){
                        $bid = new Bid([
                            'user_id' => auth()->id(),
                            'bid_amount' => $request->bid_amount,
                        ]);
                        $challenge->bids()->save($bid);
                        $message['message'] = 'You have successfully Bid on the challenge!';
                    }
                }
            }
        }
        return response($message, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
