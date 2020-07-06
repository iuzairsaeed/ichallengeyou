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
        $message['message'] = 'It\'s 1 USD for god sake. Don’t be so cheap!'; $res = 400;
        $message['premiumBtn'] = true;
        if(auth()->user()->is_premium){
            $message['premiumBtn'] = false;
            $data['message'] = 'You can\'t Vote, your own Challenge';
            if(auth()->id() <> $submitedChallenge->acceptedChallenge->user->id){ 
                $data['message'] = 'The result of this Challenge is not based on Vote';
                if($submitedChallenge->acceptedChallenge->challenge->result_type === 'vote'  ){
                    $res = 200;
                    $sub_id  = $submitedChallenge->id;
                    $voted = Vote::all()->where('user_id',auth()->id());
                    if($voted->first()){
                        $data['message'] = 'You have already voted to another challenger!';
                        if( $voted = $voted->where('submited_challenge_id',$submitedChallenge->id)->first()){
                            $vote_up = $voted->vote_up = ($voted->vote_up == false) ? true : false ;
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
                            'vote_down' => true,
                        ];
                        $this->model->create($vote);    
                    }
                }
            }
        }
        return response($data, $res);
    }

    public function voteDown(SubmitChallenge $submitedChallenge)
    {
        $message['message'] = 'It\'s 1 USD for god sake. Don’t be so cheap!'; $res = 400;
        $message['premiumBtn'] = true;
        if(auth()->user()->is_premium){
            $message['premiumBtn'] = false;
            $data['message'] = 'You can\'t Vote, your own Challenge';
            if(auth()->id() <> $submitedChallenge->acceptedChallenge->user->id){ 
                $data['message'] = 'The result of this Challenge is not based on Vote';
                if($submitedChallenge->acceptedChallenge->challenge->result_type === 'vote'  ){
                    $sub_id  = $submitedChallenge->id;
                    $voted = Vote::all()->where('user_id',auth()->id());
                    $res = 200;
                    if($voted){
                        $data['message'] = 'You have already voted to another challenger!';
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
            }
        }
        return response($data, $res);
    }
}
