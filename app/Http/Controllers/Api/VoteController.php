<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\VoteRepository;
use App\Models\Vote;
use App\Models\SubmitChallenge;
use App\Models\Challenge;

class VoteController extends Controller
{
    protected $model;

    public function __construct(Vote $model)
    {
        $this->model = new VoteRepository($model);
    }

    public function result(Challenge $challenge)
    {
        $acceptedChallenges = $challenge->acceptedChallenges;
        $total_votes = 0;
        foreach ($acceptedChallenges as $value) {
            $submitedChallenge = $value->submitChallenge->first();
            $total_votes += Vote::where('submited_challenge_id', $submitedChallenge->id)
            ->count();
        }
        $data = $this->model->getResult($challenge,$total_votes);
        return($data);
    }

    public function voteUp(SubmitChallenge $submitedChallenge) {
        $data['message'] = 'You can\'t Vote, your own Challenge'; $res = 400;
        if(auth()->id() <> $submitedChallenge->acceptedChallenge->user->id && $submitedChallenge->acceptedChallenge->challenge->result_type === 'vote'  ){
            $res = 200;
            $sub_id  = $submitedChallenge->id;
            $voted = Vote::all()->where('user_id',auth()->id());
            if($voted->first()){
                $data['message'] = 'You have already voted to another challenger!'; $res = 400;
                if( $voted = $voted->where('submited_challenge_id',$submitedChallenge->id)->first()){
                    $vote_up = $voted->vote_up = ($voted->vote_up == false) ? true : false ;
                    $vote_down = $voted->vote_down = false ;
                    $voted->update();
                    $data['message'] = ($vote_up == true) ? 'Your Vote has been casted Positive on this challenge.' : 'Your Vote has been removed' ;
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
                    'vote_down' => true,
                ];
                $this->model->create($vote);    
            }
        }
        return response($data, $res);
    }

    public function voteDown(SubmitChallenge $submitedChallenge)
    {
        $data['message'] = 'You can\'t Vote, your own Challenge';
        if(auth()->id() <> $submitedChallenge->acceptedChallenge->user->id && $submitedChallenge->acceptedChallenge->challenge->result_type === 'vote'  ){
            $sub_id  = $submitedChallenge->id;
            $voted = Vote::all()->where('user_id',auth()->id());
            if($voted){
                $data['message'] = 'You have already voted to another challenger!'; $res = 400;
                if( $voted = $voted->where('submited_challenge_id',$submitedChallenge->id)->first()){
                    $vote_down = $voted->vote_down = ($voted->vote_down == false) ? true : false ;
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
        return response($data, 200);
    }
}
