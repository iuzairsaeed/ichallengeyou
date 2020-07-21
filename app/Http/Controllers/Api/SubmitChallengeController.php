<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Repositories\ChallengeRepository;
use App\Repositories\VoteRepository;
use App\Notifications\AskCandidate;
use App\Http\Requests\Challenges\SubmitChallengeRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\SubmitChallengeCollection;
use App\Http\Resources\SubmitedVideoCollection;
use App\Http\Resources\SubmitChallengeDetailCollection;
use App\Models\Vote;
use App\Models\Amount;
use App\Models\Challenge;
use App\Models\SubmitFile;
use App\Models\SubmitChallenge;
use App\Models\AcceptedChallenge;
use App\Models\Notification;

class SubmitChallengeController extends Controller
{

    protected $model;

    public function __construct(SubmitChallenge $model) {
        $this->model = new ChallengeRepository($model);
    }

    public function result(Challenge $challenge)
    {
        $vote = new Vote;
        $this->model = new VoteRepository($vote);
        try {
            $acceptedChallenges = $challenge->acceptedChallenges;
            $total_votes = 0;
            foreach ($acceptedChallenges as $value) {
                $submitedChallenge = $value->submitChallenge;
                $total_votes += Vote::where('submited_challenge_id', $submitedChallenge->id)
                ->where('vote_up', true)
                ->count();
            }   
            $data = $this->model->getResult($challenge,$total_votes);
            return response($data,200);
        } catch (\Throwable $th) {
            return response(null,400);
        }
    }

    public function saveWinner($submitedChallenge) {
        try {
            $submitedChallenge->isWinner = true;
            $submitedChallenge->update();
            return true;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    public function getSubmitChallengerList(Challenge $challenge,Request $request){
        try {
            $acceptedChallengeModel = new AcceptedChallenge;
            $this->model = new ChallengeRepository($acceptedChallengeModel);

            $orderableCols = ['created_at'];
            $searchableCols = [];
            $whereChecks = ['challenge_id'];
            $whereOps = ['='];
            $whereVals = [$challenge->id];
            $with = [];
            $withCount = [];
            $currentStatus = [];
            $withSums = [];
            $withSumsCol = [];
            $addWithSums = [];
            $whereHas = 'submitChallenge';
            $data = $this->model->getData($request, $with, $withCount,$whereHas , $withSums, $withSumsCol, $addWithSums, $whereChecks,
            $whereOps, $whereVals, $searchableCols, $orderableCols, $currentStatus);
            collect($data['data'])->map(function ($item) {
                $item['isWinner'] = $item->submitChallenge->isWinner;
                $item['voteUp'] =  $item->submitChallenge->first()->votes()->where('vote_up' , true)->count();
                $item['voteDown'] =  $item->submitChallenge->first()->votes()->where('vote_down' , true)->count();
            });
            if($challenge->allowVoter == 'donators'){
                $message['message'] = 'You are out of time!';
                if(now() >= $challenge->after_date){
                    $data = $this->submitorList($challenge,$data);
                }
            } else if($challenge->allowVoter == 'premiumUsers'){
                $message['message'] = 'You are out of time!';
                if(now() >= $challenge->after_date->addDays(config('global.SECOND_VOTE_DURATION_IN_DAYS')) ){
                    $data = $this->submitorList($challenge,$data) ?? $data;
                }
            }
            $data['title'] = $challenge->title ?? '-';
            $data['data'] = SubmitChallengeCollection::collection($data['data']);
            return response($data,$data['response']);
        } catch (\Throwable $th) {
            return response(['message'=>$th->getMessage()],400);
        }
    }

    public function submitorList($challenge,$data) {
        $count = 0; $isWinner = 0;
        $results = $this->result($challenge)->original;
        if($results){
            foreach ($results as $result) {
                if(($result['result'] >= 40 && $result['result'] <= 60) || $result['result'] >= 51 ){
                    $count++;   
                }
            }
            foreach ($data['data'] as $d) {
                if($d->submitChallenge->isWinner){
                    $isWinner++;
                }
            }
            if($isWinner <> 1){
                if($count == 1){
                    foreach ($data['data'] as $value) {
                        foreach ($results as $result) {
                            if($value->user_id == $result['id'] && $result['result'] >= 51){
                                $submitedChallenge = $value->submitChallenge;
                                $this->saveWinner($submitedChallenge);
                                $value['isWinner'] = true;
                            }
                        }
                    }
                } else {
                    $isNotification = Notification::where('notifiable_id', $data['data'][0]->submitChallenge->id )
                        ->where('notifiable_type', 'App\Models\SubmitChallenge' )
                        ->where('click_action', 'ASK_RESULT_DIALOG' )
                        ->exists();
                    if(!$isNotification){
                        foreach ($data['data'] as $challenger) {
                            $notification = new Notification([
                                'user_id' => $challenger->user->id,
                                'title' => 'Result has been tied', 
                                'body' => 'Result has been tied, Do you want to ask the App Admin to Evaluate or The Public?',
                                'click_action' => 'ASK_RESULT_DIALOG', 
                                'data_id' => $challenger->id,
                            ]);
                            $challenger->user->notify(new AskCandidate);
                            $challenger->submitChallenge->notifications()->save($notification);
                        }
                        $adminNotification = new Notification([
                            'user_id' => 1,
                            'title' => 'Result has been tied', 
                            'body' => 'Result has been tied on the challenge ',
                            'click_action' => 'CHALLENGE_DETAIL_SCREEN', 
                            'data_id' => $challenger->id,
                        ]);
                        $challenger->submitChallenge->notifications()->save($adminNotification);
                    }
                }
            }
        }
        return $data;
    }

    public function getSubmitChallengeDetail(AcceptedChallenge $acceptedChallenge){
        try {
            $data['data'] = $acceptedChallenge;
            if($data['data']->submitChallenge->first()){
                collect($data)->map(function($item) {
                    $item['submited_challenge_id'] = $item->submitChallenge->id;
                    $item['title'] = $item->challenge->title;
                    $item['description'] = $item->challenge->description;
                    $item['submit_date'] = $item->submitChallenge->created_at->format('Y-m-d H:i A');
                    $item['files'] = $item->submitFiles->pluck('file');
                    $item['voteUp'] = $item->submitChallenge->first()->votes()
                    ->where('user_id',auth()->id())
                    ->where('vote_up',true)->first();
                    $item['voteDown'] = $item->submitChallenge->first()->votes()
                    ->where('user_id',auth()->id())
                    ->where('vote_down',true)->first();
                    $item['voteBtn'] = ($item->challenge->result_type === 'vote') ? true : false;
                });
                   $data = SubmitChallengeDetailCollection::collection($data);
                return response($data,200);
            }
            return response(['message'=>'There is No Submited Challenge'], 207);
        } catch (\Throwable $th) {
            return response($th->getMessage(),207);
        }
    }

    public function postSubmitChallenge(Challenge $challenge)
    {
        try {
            $data['message'] = 'It\'s 1 USD for god sake. Don’t be so cheap!'; $res = 400;
            $data['premiumBtn'] = true;
            if(auth()->user()->is_premium){
                $data['premiumBtn'] = false;
                $data['message']='You have Already Submitted the Challenge!'; $res = 400;
                if(!$challenge->acceptedChallenges->where('user_id', auth()->id())->first()->submitChallenge()->exists()){
                    $data['message'] = 'No Video Uploaded!';
                    if ($challenge->acceptedChallenges->where('user_id', auth()->id())->first()->submitFiles()->exists()) {
                        $data['message'] = 'You are out of time!';
                        $after_date = $challenge->after_date;
                        if(now() >=  $challenge->start_time && now() <= $after_date){
                            $data['accepted_challenge_id'] = $challenge->acceptedChallenges->where('user_id', auth()->id())->first()->id;
                            $this->model->create($data);
                            $challenge->acceptedChallenges()->where('user_id', auth()->id())->first()->setStatus(Submitted());
                            $data['message']='You have Successfuly Submitted the Challenge!'; $res = 200;
                        }
                    }
                }
            }
        } catch (\Throwable $th) {
            $data['message'] = 'You need to accept challenge first';
            $res = 400;
        }
        return response($data,$res);
    }

    public function getVideo(Challenge $challenge)
    {
        try {
            $data['file'] = $challenge->acceptedChallenges->where('user_id', auth()->id())->first()->submitFiles()->get();
            $data['file'] = SubmitedVideoCollection::collection($data['file']);
            return response($data,200);
        } catch (\Throwable $th) {
            return $th->getMessage();
            return response(['message'=>'No Video Found!'],207);
        }
    }

    public function addVideo(Challenge $challenge, SubmitChallengeRequest $request)
    {
        try {
            $data['message'] = 'It\'s 1 USD for god sake. Don’t be so cheap!'; $res = 400;
            $data['premiumBtn'] = true;
            if(auth()->user()->is_premium){
                $data['premiumBtn'] = false;
                $isDonator = Amount::where('user_id', auth()->id())->where('challenge_id', $challenge->id)->exists();
                $message['message'] = 'You\'re Donator! You Can\'t Accept This Challenge!';
                if(!$isDonator){
                    $data['message'] = 'You can\'t add video due to Submited Challenge!'; $res = 400;
                    if(!optional($challenge->acceptedChallenges()->where('user_id', auth()->id())->first())->submitChallenge){
                        $files = $request->file;
                        $after_date = $challenge->after_date;
                        $data['message'] = 'You are out of time!'; $res = 400;
                        if(now() >=  $challenge->start_time && now() <= $after_date){
                            $file = uploadFile($files, SubmitChallengesPath(), null);
                            $records = new SubmitFile ([
                                'accepted_challenge_id' => $challenge->acceptedChallenges->where('user_id', auth()->id())->first()->id,
                                'file' => $file,
                                'created_at' => now(),
                            ]); 
                            $challenge->acceptedChallenges()->where('user_id', auth()->id())->first()->submitFiles()->save($records);
                            $data['message'] = 'Video has been Added!';$res = 200;
                        }
                    }
                }
            }
        } catch (\Throwable $th) {
            return response($th, $res);
            $data['message'] = 'You need to accept challenge first';
            $res = 400;
        }    
        return response($data, $res);
    }
    
    public function deleteVideo(SubmitFile $file)
    {
        if(now() >=  $challenge->start_time && now() <= $after_date){
            $file_name = $file->file;
            deleteFile($file_name, SubmitChallengesPath());
            $file->delete();
            return response(['message'=>'Video is Deleted!']);
        }
        return response(['message'=>'You\'re out of time !']);
    }

}
