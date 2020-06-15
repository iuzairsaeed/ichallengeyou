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

    public function vote(SubmitChallenge $submitedChallenge,Request $request) {
        $data['message'] = 'You can\'t. Vote!';
        if(!auth()->id() == $submitedChallenge->acceptedChallenge->user_id){
            $sub_id  = $submitedChallenge->id;
            $vote    = $request->vote;
            $voted = Vote::where('user_id',auth()->id())
            ->where('submited_challenge_id',$submitedChallenge->id)
            ->first();
            $data['message'] = 'You have already voted';
            if($voted){
                if($voted->vote != $vote){
                    $voted->vote = $vote;
                    $voted->update();
                    $data['message'] = ($vote == true) ? 'Your Vote has been casted Positive on this challenge.' : 'Your Vote has been casted Negative on this challenge' ;
                    $data['vote'] = $vote;
                }
                return response($data, 208);
            } else {
                $data['message'] = 'Your Vote has been cast.';
                $vote = [
                    'user_id' => auth()->id(),
                    'submited_challenge_id' => $sub_id,
                    'vote' => $vote
                ];
                $this->model->create($vote);    
                return response($data, 200);
            }
        }
        return response($data, 200);
    }
}
