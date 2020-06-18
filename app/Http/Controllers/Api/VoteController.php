<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\ChallengeRepository;
use App\Http\Resources\TransactionCollection;
use Carbon\Carbon;
use App\Models\Vote;
use App\Models\SubmitChallenge;

class VoteController extends Controller
{
    protected $model;

    public function __construct(Vote $model)
    {
        $this->model = new ChallengeRepository($model);
    }

    public function voteUp(SubmitChallenge $submitedChallenge) {
        $data['message'] = 'You can\'t Vote, your own Challenge';
        if(auth()->id() <> $submitedChallenge->acceptedChallenge->user->id){
            $sub_id  = $submitedChallenge->id;
            $voted = Vote::where('user_id',auth()->id())
            ->where('submited_challenge_id',$submitedChallenge->id)
            ->first();
            if($voted){
                $vote_up = $voted->vote_up = ($voted->vote_up == false) ? true : false ;
                $vote_down = $voted->vote_down = false ;
                $voted->update();
                $data['message'] = ($vote_up == true) ? 'Your Vote has been casted Positive on this challenge.' : 'Your Vote has been removed' ;
                $data['vote_up'] = $vote_up;
                $data['vote_down'] = $vote_down;
            } else {
                $data['message'] = 'Your Vote has been casted Positive on this Challenge!';
                $data['vote_down'] = false;
                $data['vote_up'] = true;
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

    public function voteDown(SubmitChallenge $submitedChallenge)
    {
        $data['message'] = 'You can\'t Vote, your own Challenge';
        if(auth()->id() <> $submitedChallenge->acceptedChallenge->user->id){
            $sub_id  = $submitedChallenge->id;
            $voted = Vote::where('user_id',auth()->id())
            ->where('submited_challenge_id',$submitedChallenge->id)
            ->first();
            if($voted){
                $vote_down = $voted->vote_down = ($voted->vote_down == false) ? true : false ;
                $vote_up = $voted->vote_up = false ;
                $voted->update();
                $data['message'] = ($vote_down == true) ? 'Your Vote has been casted Negative on this challenge.' : 'Your Vote has been removed' ;
                $data['vote_up'] = $vote_up;
                $data['vote_down'] = $vote_down;
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
