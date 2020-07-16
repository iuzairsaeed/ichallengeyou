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
        $challenger = $submitedChallenge->acceptedChallenge->where('user_id',auth()->id())->first();
        $data['message'] = 'Challenger Can\'t Vote!';$res = 400;
        if(!$challenger){
            $donator = Amount::where('user_id',auth()->id())->where('challenge_id',$challenge->id)->first();
            $data['message'] = 'It\'s 1 USD for god sake. Don’t be so cheap!'; 
            $data['premiumBtn'] = true;
            if(auth()->user()->is_premium){
                $data['premiumBtn'] = false;
                $data['message'] = 'You can\'t Vote, your own Challenge';
                if(auth()->id() <> $submitedChallenge->acceptedChallenge->user->id){ 
                    $data['message'] = 'The result of this Challenge is not based on Vote';
                    if($submitedChallenge->acceptedChallenge->challenge->result_type === 'vote'){
                        $data['message'] = 'Only Donator can Vote on this Challenge';
                        if($donator){
                            $data['message'] = 'You\'re out of time.';
                            if(now() <= $submitedChallenge->acceptedChallenge->challenge->after_date){
                                $res = 200;
                                $sub_id  = $submitedChallenge->id;
                                $acceptedChallenges = $submitedChallenge->acceptedChallenge->challenge->acceptedChallenges;
                                $isVoted = 0;
                                $voters = Vote::where('user_id',auth()->id())->get();
                                if($voters->first()) {
                                    foreach($acceptedChallenges as $acceptedChallenge){
                                        foreach($voters as $voter){
                                            if($voter->submited_challenge_id === $acceptedChallenge->submitChallenge->id){
                                                $isVoted++;
                                            }
                                        }
                                    }
                                    if($isVoted >= 1){
                                        $data['message'] = 'You have already voted to challenger!';
                                    }
                                    $voted = $voter->where('submited_challenge_id',$submitedChallenge->id)->first();
                                    if($voted){
                                        $vote_up = $voted->vote_up = ($voted->vote_up == false) ? true : false ;
                                        ($vote_up == true) ? true : $voted->delete();
                                        $vote_down = $voted->vote_down = false ;
                                        $voted->update();
                                        $data['message'] = ($vote_up == true) ? 'Your Vote has been casted Positive on this challenge.' : 'Your Vote has been removed' ;
                                        $res = 200;
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
                            } 
                        }
                    }
                }
            }
        }
        return response($data, $res);
    }

    public function voteDown(SubmitChallenge $submitedChallenge) {
        $challenge = $submitedChallenge->acceptedChallenge->challenge;
        $challenger = $submitedChallenge->acceptedChallenge->where('user_id',auth()->id())->first();
        $data['message'] = 'Challenger Can\'t Vote!';$res = 400;
        if(!$challenger){
            $donator = Amount::where('user_id',auth()->id())->where('challenge_id',$challenge->id)->first();
            $data['message'] = 'It\'s 1 USD for god sake. Don’t be so cheap!'; 
            $data['premiumBtn'] = true;
            if(auth()->user()->is_premium){
                $data['premiumBtn'] = false;
                $data['message'] = 'You can\'t Vote, your own Challenge';
                if(auth()->id() <> $submitedChallenge->acceptedChallenge->user->id){ 
                    $data['message'] = 'The result of this Challenge is not based on Vote';
                    if($submitedChallenge->acceptedChallenge->challenge->result_type === 'vote'  ){
                        $data['message'] = 'Only Donator can Vote on this Challenge';
                        if($donator){
                            if(now() <= $submitedChallenge->acceptedChallenge->challenge->after_date){
                                $res = 200;
                                $sub_id  = $submitedChallenge->id;
                                $acceptedChallenges = $submitedChallenge->acceptedChallenge->challenge->acceptedChallenges;
                                $isVoted = 0;
                                $voters = Vote::where('user_id',auth()->id())->get();
                                if($voters->first()) {
                                    foreach($acceptedChallenges as $acceptedChallenge){
                                        foreach($voters as $voter){
                                            if($voter->submited_challenge_id === $acceptedChallenge->submitChallenge->id){
                                                $isVoted++;
                                            }
                                        }
                                    }
                                    if($isVoted >= 1){
                                        $data['message'] = 'You have already voted to challenger!';
                                    }
                                    $voted = $voter->where('submited_challenge_id',$submitedChallenge->id)->first();
                                    if($voted){
                                        $vote_down = $voted->vote_down = ($voted->vote_down == false) ? true : false ;
                                        ($vote_down == true) ? true : $voted->delete();
                                        $vote_up = $voted->vote_up = false ;
                                        $voted->update();
                                        $data['message'] = ($vote_down == true) ? 'Your Vote has been casted Negative on this challenge.' : 'Your Vote has been removed' ;
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
                            }
                        }
                    }
                }
            }
        }
        return response($data, $res);
    }
}
