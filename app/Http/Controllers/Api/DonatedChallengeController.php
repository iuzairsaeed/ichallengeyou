<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\ChallengeRepository;
use App\Http\Resources\ChallengeDonated;
use Illuminate\Http\Request;
use App\Models\Amount;

class DonatedChallengeController extends Controller
{

    protected $model;

    public function __construct(Amount $model) {
        $this->model = new ChallengeRepository($model);
    }
    
    public function donatedChallenge(Request $request)
    {
        $orderableCols = ['created_at', 'title', 'start_time', 'user.name', 'trend', 'amounts_sum', 'amounts_trend_sum'];
        $searchableCols = ['title'];
        $whereChecks = ['user_id','type'];
        $whereOps = ['=','='];
        $whereVals = [auth()->id(),'donation'];
        $with = ['challenge'];
        $withCount = [];
        $groupByVals = [];
        $currentStatus = [];
        $sums = ['amount'];
        $sumCol = [];
        $withSums = [];
        $withSumsCol = [];
        $addWithSums = [];

        $data = $this->model->getDonated($request, $with, $withCount,$sums ,$sumCol,$groupByVals, $withSums, $withSumsCol, $addWithSums, $whereChecks,
                $whereOps, $whereVals, $searchableCols, $orderableCols, $currentStatus);
        collect($data['data'])->map(function ($item) {
            $item['sum'] = config('global.CURRENCY').$item->sum;
            return $item;
        });
        $data['data'] = ChallengeDonated::collection($data['data']);
        return response($data, 200);

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
    public function store(Request $request)
    {
        //
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
