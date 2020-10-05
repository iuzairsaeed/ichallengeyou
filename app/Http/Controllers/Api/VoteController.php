<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\VoteRepository;
use App\Models\Vote;
use App\Models\Amount;
use App\Models\Challenge;
use App\Models\SubmitChallenge;

class VoteController extends Controller
{
    protected $model;

    public function __construct(Vote $model)
    {
        $this->model = new VoteRepository($model);
    }

    public function result(Challenge $challenge)
    {
        try {
            $acceptedChallenges = $challenge->acceptedChallenges;
            $total_votes = 0;
            foreach ($acceptedChallenges as $value) {
                $submitedChallenge = $value->submitChallenge->first();
                $total_votes += Vote::where('submited_challenge_id', $submitedChallenge->id)
                ->count();
            }
            $data = $this->model->getResult($challenge,$total_votes);
            return response($data,200);
        } catch (\Throwable $th) {
            $data['message'] = 'No Votes Count!';
            return response($data,207);
        }
        
    }

    public function voteUp(SubmitChallenge $submitedChallenge) {
        $challenge = $submitedChallenge->acceptedChallenge->challenge;
        $challenger = $submitedChallenge->acceptedChallenge->where('user_id',auth()->id())->where('challenge_id', $challenge->id )->first();
        $data['message'] = config('global.CHALLENGER_CANNOT_VOTE_MESSAGE');$res = 400;

        if(!$challenger){
            $data['message'] = config('global.PREMIUM_USER_MESSAGE'); 
            $data['premiumBtn'] = true;
            if(auth()->user()->is_premium){
                $data['premiumBtn'] = false;
                $data['message'] = config('global.CANNOT_VOTE_OWN_CHALLENGE_MESSAGE');
                if(auth()->id() <> $submitedChallenge->acceptedChallenge->user->id){ 
                    $data['message'] =config('global.VOTE_CHALLENGE_TYPE_MESSAGE');
                    if($submitedChallenge->acceptedChallenge->challenge->result_type === 'vote'){
                        if($submitedChallenge->acceptedChallenge->challenge->allowVoter == 'donators'){
                            $donator = Amount::where('user_id',auth()->id())->where('challenge_id',$challenge->id)->first();
                            $data['message'] = config('global.DONATOR_CAN_VOTE_MESSAGE');
                            if($donator){
                                $data['message'] = 'You\'re out of time.';
                                if(now() <= $submitedChallenge->acceptedChallenge->challenge->after_date){
                                    $data = $this->votingUp($submitedChallenge);$res = 200;
                                }
                            }
                        // } else if($submitedChallenge->acceptedChallenge->challenge->allowVoter == 'premiumUsers'){
                        //     $data['message'] = 'You\'re out of time.';
                        //     if(now() <= $submitedChallenge->acceptedChallenge->challenge->after_date->addDays(config('global.SECOND_VOTE_DURATION_IN_DAYS')) ){
                        //         $data = $this->votingUp($submitedChallenge);$res = 200;
                        //     }
                        } else {
                            $data['message'] = config('global.ADMIN_DECIDE_WINNER_MESSAGE');
                        }
                    }
                    
                }
            }
        }
        return response($data, $res);
    }

    function votingUp($submitedChallenge) {
        $res = 200;
        $sub_id  = $submitedChallenge->id;
        $isVoted = 0;
        $acceptedChallenges = $submitedChallenge->acceptedChallenge->challenge->acceptedChallenges;
        $voters = Vote::where('user_id',auth()->id())->get();

        foreach($acceptedChallenges as $acceptedChallenge){
            foreach($voters as $voter){
                if($voter->submited_challenge_id == optional($acceptedChallenge->submitChallenge)->id){
                    $isVoted++;
                }
            }
        }
        if($isVoted >= 1){
            $data['message'] = config('global.ALREADY_VOTE_MESSAGE');
            $voted = $voter->where('submited_challenge_id',$submitedChallenge->id)
            ->where('user_id',auth()->id())
            ->first();
            if($voted){
                $vote_up = $voted->vote_up = ($voted->vote_up == false) ? true : false ;
                ($vote_up == true) ? true : $voted->delete();
                $vote_down = $voted->vote_down = false ;
                $voted->update();
                $data['message'] = ($vote_up == true) ? config('global.VOTE_CAST_POSITIVE_MESSAGE') : config('global.VOTE_REMOVED_MESSAGE') ;
                $data['vote_up'] = $vote_up;
                $data['vote_down'] = $vote_down;
            }
        } else {
            $data['message'] = 'Your Vote has been casted Positive on this Challenge!';
            $data['vote_up'] = true;
            $data['vote_down'] = false;
            $vote = [
                'user_id' => auth()->id(),
                'submited_challenge_id' => $sub_id,
                'vote_up' => true,
            ];
            $this->model->create($vote);    
        }
        return $data;
    }

    public function voteDown(SubmitChallenge $submitedChallenge) {
        $challenge = $submitedChallenge->acceptedChallenge->challenge;
        $challenger = $submitedChallenge->acceptedChallenge->where('user_id',auth()->id())->where('challenge_id', $challenge->id )->first();
        $data['message'] = config('global.CHALLENGER_CANNOT_VOTE_MESSAGE');$res = 400;
        if(!$challenger){
            $donator = Amount::where('user_id',auth()->id())->where('challenge_id',$challenge->id)->first();
            $data['message'] = config('global.PREMIUM_USER_MESSAGE'); 
            $data['premiumBtn'] = true;
            if(auth()->user()->is_premium){
                $data['premiumBtn'] = false;
                $data['message'] = config('global.CANNOT_VOTE_OWN_CHALLENGE_MESSAGE');
                if(auth()->id() <> $submitedChallenge->acceptedChallenge->user->id){ 
                    $data['message'] =config('global.VOTE_CHALLENGE_TYPE_MESSAGE');
                    if($submitedChallenge->acceptedChallenge->challenge->result_type === 'vote'  ){
                        if($submitedChallenge->acceptedChallenge->challenge->allowVoter == 'donators'){
                            $data['message'] = 'You\'re out of time.';
                            if(now() <= $submitedChallenge->acceptedChallenge->challenge->after_date){
                                $data = $this->votingDown($submitedChallenge);$res = 200;
                            }
                        // } else if($submitedChallenge->acceptedChallenge->challenge->allowVoter == 'premiumUsers'){
                        //     $donator = Amount::where('user_id',auth()->id())->where('challenge_id',$challenge->id)->first();
                        //     $data['message'] = config('global.DONATOR_CAN_VOTE_MESSAGE');
                        //     if($donator){
                        //         $data['message'] = 'You\'re out of time.';
                        //         if(now() <= $submitedChallenge->acceptedChallenge->challenge->after_date->addDays(config('global.SECOND_VOTE_DURATION_IN_DAYS')) ){
                        //             $data = $this->votingDown($submitedChallenge);$res = 200;
                        //         }
                        //     }
                        } else {
                            $data['message'] = config('global.ADMIN_DECIDE_WINNER_MESSAGE');
                        }
                    }
                }
            }
        }
        return response($data, $res);
    }

    function votingDown($submitedChallenge) {
        $res = 200;
        $sub_id  = $submitedChallenge->id;
        $isVoted = 0;
        $acceptedChallenges = $submitedChallenge->acceptedChallenge->challenge->acceptedChallenges;
        $voters = Vote::where('user_id',auth()->id())->get();
        
        foreach($acceptedChallenges as $acceptedChallenge){
            foreach($voters as $voter){
                if($voter->submited_challenge_id == optional($acceptedChallenge->submitChallenge)->id){
                    $isVoted++;
                }
            }
        }
        if($isVoted >= 1) {
            $data['message'] = config('global.ALREADY_VOTE_MESSAGE');
            $voted = $voter->where('submited_challenge_id',$submitedChallenge->id)
            ->where('user_id',auth()->id())
            ->first();
            if($voted){
                $vote_down = $voted->vote_down = ($voted->vote_down == false) ? true : false ;
                ($vote_down == true) ? true : $voted->delete();
                $vote_up = $voted->vote_up = false ;
                $voted->update();
                $data['message'] = ($vote_down == true) ? config('global.VOTE_CAST_NEGATIVE_MESSAGE') : config('global.VOTE_REMOVED_MESSAGE') ;
                $data['vote_up'] = $vote_up;
                $data['vote_down'] = $vote_down;
            }
        } else {
            $data['message'] = 'Your Vote has been casted Negative on this Challenge!';
            $data['vote_down'] = true;
            $data['vote_up'] = false;
            $vote = [
                'user_id' => auth()->id(),
                'submited_challenge_id' => $sub_id,
                'vote_down' => true,
            ];
            $this->model->create($vote);    
        }
        return $data;
    }
}
