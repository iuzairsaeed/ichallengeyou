<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AskCandidate;
use App\Models\Challenge;
use App\Repositories\Repository;

class AskCandidateController extends Controller
{
    protected $model;

    public function __construct(AskCandidate $askCandidate) {
        $this->model = new Repository($askCandidate);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->model->all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Challenge $challenge,Request $request)
    {
        $message['message'] = 'You\'re out of time!';
        if( now() <= $challenge->start_time->addMinutes(config('global.ASK_CANDIDATES_DURATION_IN_HOURS')) ) {
            $isVote = AskCandidate::where('user_id',auth()->id())->where('challenge_id',$challenge->id)->first();
            $message['message'] = 'Thanks, We have already got your Recommendation';
            if(!$isVote){
                $message['message'] = 'Thanks for your Vote';
                $data = [
                    'user_id' => auth()->id(),
                    'challenge_id' => $challenge->id,
                    'vote' => $request->vote,
                ]; 
                $this->model->create($data);
            }
        }
        return response($message,200);
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

    public function result(Challenge $challenge) {

        $admin = AskCandidate::where('challenge_id',$challenge->id)
                ->where('vote','admin')->count();
        $premiumUsers = AskCandidate::where('challenge_id',$challenge->id)
                ->where('vote','premiumUsers')->count();
        $result = $admin <=> $premiumUsers;
        if($result == -1){
            $message['message'] = 'Premium Users will decide the winner by vote';
            $challenge->allowVoter = 'premiumUsers';
            $challenge->update();
        } elseif ($result == 0 || $result == 1 ) {
            $message['message'] = 'Admin will decide the Winner';
            $challenge->allowVoter = 'admin';
            $challenge->update();
        }
        return response($message,200);
    }
}
