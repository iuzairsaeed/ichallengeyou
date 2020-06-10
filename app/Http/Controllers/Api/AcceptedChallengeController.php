<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\ChallengeAccepted;
use App\Repositories\ChallengeRepository;
use App\Models\AcceptedChallenge;
use App\Models\Amount;
use Illuminate\Support\Facades\Auth;

class AcceptedChallengeController extends Controller
{

    protected $model;

    public function __construct(AcceptedChallenge $model) {
        $this->model = new ChallengeRepository($model);
    }

    public function accept(int $id)
    {
        if(AcceptedChallenge::where('challenge_id', 3)->where('user_id' , auth()->id())->exists()){
            return 1;
        }
        return 0;
        if(Auth::user()->is_premium){
            $record = [
                'challenge_id' => $id,
                'user_id' => auth()->id(),
            ];
            $acceptedChallenge = $this->model->create($record);
            $acceptedChallenge->setStatus(Accepted());
            return response('You have successfully accepted the challenge!',200);
        }
        return response("Become one now, its 1 USD for god sake. Don’t be so cheap",400);
    }

    public function acceptedChallenge(Request $request)
    {
        $orderableCols = ['created_at', 'title', 'start_time', 'user.name', 'trend', 'amounts_sum', 'amounts_trend_sum'];
        $searchableCols = ['title'];
        $whereChecks = ['user_id'];
        $whereOps = ['='];
        $whereVals = [auth()->id()];
        $with = [];
        $withCount = [];
        $currentStatus = [];
        $withSums = ['amounts'];
        $withSumsCol = ['amount'];
        $addWithSums = [];

        $data = $this->model->getData($request, $with, $withCount, $withSums, $withSumsCol, $addWithSums, $whereChecks,
                $whereOps, $whereVals, $searchableCols, $orderableCols, $currentStatus);

        $serial = ($request->start ?? 0);
        collect($data['data'])->map(function ($item) use (&$serial) {
            $item['serial'] = $serial++;
            $item['amounts_sum'] = config('global.CURRENCY').$item->amounts_sum;
            return $item;
        });
        $data['data'] = ChallengeAccepted::collection($data['data']);
        return response($data, 200);
        
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
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
