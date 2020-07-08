<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\ChallengeAccepted;
use App\Repositories\ChallengeRepository;
use App\Models\AcceptedChallenge;
use App\Models\Amount;
use App\Models\Challenge;

class AcceptedChallengeController extends Controller
{

    protected $model;

    public function __construct(AcceptedChallenge $model) {
        $this->model = new ChallengeRepository($model);
    }

    public function accept(Challenge $challenge)
    {
        try {
            $message['message'] = 'It\'s 1 USD for god sake. Don’t be so cheap!';$res = 400;
            $message['premiumBtn'] = true;
            $message['submitBtn'] = false;
            if(auth()->user()->is_premium){
                $message['premiumBtn'] = false;
                $isDonator = Amount::where('user_id', auth()->id())->where('challenge_id', $challenge->id)->exists();
                $message['message'] = 'You\'re Donator! You Can\'t Accept This Challenge!';
                if(!$isDonator){
                    $message['message'] = 'You Can\'t Accept Your Own Challenge!';
                    if ($challenge->user_id != auth()->id()) {
                        $message['message'] = 'You have Already accepted This Challenge!';
                        if($challenge->user_id != auth()->id() && !$challenge->acceptedChallenges()->where('user_id', auth()->id())->exists()){
                            $message['message'] = 'You are out of time!';
                            $after_date = $challenge->after_date;
                            if(now() <= $after_date){
                                $acceptedChallenge = new AcceptedChallenge([
                                    'user_id' => auth()->id(),
                                ]);
                                $challenge->acceptedChallenges()->save($acceptedChallenge);
                                $message['message'] = 'You have successfully accepted the challenge!';$res = 200;
                                if(now() >=  $challenge->start_time && now() <= $challenge->after_date){
                                    $message['submitBtn'] = true;
                                }
                            }
                        }
                    }
                }
            }
            return response($message, $res);
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
        $whereHas = null;

        $data = $this->model->getData($request, $with, $withCount, $whereHas, $withSums, $withSumsCol, $addWithSums, $whereChecks,
                $whereOps, $whereVals, $searchableCols, $orderableCols, $currentStatus);

        $serial = ($request->start ?? 0);
        collect($data['data'])->map(function ($item) use (&$serial) {
            $item['serial'] = $serial++;
            $item['amounts_sum'] = config('global.CURRENCY').$item->amounts_sum;
            return $item;
        });
        $data['data'] = ChallengeAccepted::collection($data['data']);
        return response($data, $data['response']);
        
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
