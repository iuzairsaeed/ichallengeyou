<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\ChallengeAccepted;
use App\Repositories\ChallengeRepository;
use App\Models\AcceptedChallenge;
use App\Models\Amount;
use App\Models\Challenge;
use Illuminate\Support\Facades\Auth;

class AcceptedChallengeController extends Controller
{

    protected $model;

    public function __construct(AcceptedChallenge $model) {
        $this->model = new ChallengeRepository($model);
    }

    public function accept(Challenge $challenge)
    {
        try {
            $message['message'] = 'You Can\'t Accept This Challenge!';
            if($challenge->user_id != auth()->id() && !$challenge->acceptedChallenges()->where('user_id', auth()->id())->exists()){
                $message['message'] = 'You are out of time!';
                $before_date = $challenge->start_time;
                $after_date = $before_date->addDays($challenge->duration_days)
                ->addHours($challenge->duration_hours)
                ->addMinutes($challenge->duration_minutes);
                if(now() <= $after_date){
                    $acceptedChallenge = new AcceptedChallenge([
                        'user_id' => auth()->id(),
                    ]);
                    $challenge->acceptedChallenges()->save($acceptedChallenge);
                    $message['message'] = 'You have successfully accepted the challenge!';
                }
            }
            return response($message, 200);
        } catch (\Throwable $th) {
            return response($th->getMessage(), 422);
        }
        
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
