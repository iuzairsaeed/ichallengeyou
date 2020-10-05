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
use App\Models\User;
use App\Models\Vote;
use App\Models\Amount;
use App\Models\Challenge;
use App\Models\SubmitFile;
use App\Models\SubmitChallenge;
use App\Models\AcceptedChallenge;
use App\Models\Notification;
use Notification as Notifications;

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
            $withTrash = false;
            
            $data = $this->model->getData($request, $with, $withTrash, $withCount, $whereHas, $withSums, $withSumsCol, $addWithSums, $whereChecks,
            $whereOps, $whereVals, $searchableCols, $orderableCols, $currentStatus);
            collect($data['data'])->map(function ($item) {
                $item['isWinner'] = $item->submitChallenge->isWinner;
                $item['voteUp'] =  $item->submitChallenge->votes()->where('vote_up' , true)->count();
                $item['voteDown'] =  $item->submitChallenge->votes()->where('vote_down' , true)->count();
            });
            // if($challenge->result_type == 'vote'){
            //     if($challenge->allowVoter == 'donators'){
            //         $message['message'] = config('global.TIMEOUT_MESSAGE');
            //         if(now() >= $challenge->after_date){
            //             $this->submitorList($challenge,$data);
            //         }
            //     } else if($challenge->allowVoter == 'premiumUsers'){
            //         $message['message'] = config('global.TIMEOUT_MESSAGE');
            //         if(now() >= $challenge->after_date->addDays(config('global.SECOND_VOTE_DURATION_IN_DAYS')) ){
            //             $this->submitorList($challenge,$data);
            //         }
            //     }
            // }
            $data['title'] = $challenge->title ?? '-';
            $data['result_type'] = $challenge->result_type ?? '-';
            $data['data'] = SubmitChallengeCollection::collection($data['data']);
            return response($data,$data['response']);
        } catch (\Throwable $th) {
            return response(['message'=>$th->getMessage()],400);
        }
    }



    public function submitorList($challenge,$data) {

        $count = 0; $isWinner = 0;
        $results = $this->result($challenge)->original;
        dd($results);
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
                    foreach ($data['data'] as $challenger) {
                        if($challenger->submitChallenge){
                            $isNotification = Notification::where('notifiable_id', $challenger->submitChallenge->id )
                            ->where('notifiable_type', 'App\Models\SubmitChallenge' )
                            ->where('click_action', 'ASK_RESULT_DIALOG' )
                            ->exists();
                            if(!$isNotification){
                                // SEND NOTIFICATION TO SUBMITOR
                                $notification = new Notification([
                                    'user_id' => $challenger->user->id,
                                    'title' => 'Result has been tied',
                                    'body' => 'Result has been tied of challenge '.$challenge->title.', Do you want to ask the App Admin to Evaluate or The Public?',
                                    'click_action' => 'ASK_RESULT_DIALOG',
                                    'data_id' => $challenger->id,
                                ]);
                                $notify_user = User::find($challenger->user->id);
                                Notifications::send($notify_user, new AskCandidate($challenge->id));
                                $challenger->submitChallenge->notifications()->save($notification);
                            }
                        }
                    }   
                    // SEND NOTIFICATION TO CREATOR
                    $isNotification = Notification::where('notifiable_id', $challenge->id )
                    ->where('notifiable_type', 'App\Models\Challenge' )
                    ->where('click_action', 'CHALLENGE_DETAIL_SCREEN' )
                    ->where('title', 'Result Still Pending' )
                    ->where('user_id', $challenge->user->id )
                    ->exists();
                    if(!$isNotification){
                        # Send notification to creator 
                        $notification = new Notification([
                            'user_id' => $challenge->user->id,
                            'title' => 'Result Still Pending',
                            'body' => 'Result has been tied of challenge '.$challenge->title,
                            'click_action' => 'CHALLENGE_DETAIL_SCREEN',
                            'data_id' => $challenge->id,
                        ]);
                        $notify_user = User::find($challenge->user->id);
                        Notifications::send($notify_user, new AskCandidate($challenge->id));
                        $challenge->notifications()->save($notification);
                    }
                    // SEND NOTIFICATION TO ADMIN
                    $isNotification = Notification::where('notifiable_id', $challenge->id )
                    ->where('notifiable_type', 'App\Models\Challenge' )
                    ->where('click_action', 'CHALLENGE_DETAIL_SCREEN' )
                    ->where('title', 'Result Still Pending' )
                    ->where('user_id', 1 )
                    ->exists();
                    if(!$isNotification){
                        $adminNotification = new Notification([
                            'user_id' => 1,
                            'title' => 'Result Still Pending',
                            'body' => 'Result has been tied of the challenge '.$challenge->title,
                            'click_action' => 'CHALLENGE_DETAIL_SCREEN',
                            'data_id' => $challenge->id,
                        ]);
                        $challenge->notifications()->save($adminNotification);
                    }
                }
            }
        } 
        // else {
        //     foreach ($data['data'] as $acceptedChallenge) {
        //         if($acceptedChallenge->submitChallenge){
        //             $isNotification = Notification::where('notifiable_id', $acceptedChallenge->submitChallenge->id )
        //             ->where('notifiable_type', 'App\Models\SubmitChallenge' )
        //             ->where('click_action', 'CHALLENGE_DETAIL_SCREEN' )
        //             ->exists();
        //             if(!$isNotification){
        //                 # Send Notification to Submitor
        //                 $notification = new Notification([
        //                     'user_id' => $acceptedChallenge->user->id,
        //                     'title' => 'Result has been tied',
        //                     'body' => 'Result has been tied of challenge '.$challenge->title.', Do you want to ask the App Admin to Evaluate or The Public?',
        //                     'click_action' => 'CHALLENGE_DETAIL_SCREEN',
        //                     'data_id' => $challenge->id,
        //                 ]);
        //                 $notify_user = User::find($acceptedChallenge->user->id);
        //                 Notifications::send($notify_user, new AskCandidate($challenge->id));
        //                 $acceptedChallenge->submitChallenge->notifications()->save($notification);
        //             }
        //         }
        //     }
        //     $isNotification = Notification::where('notifiable_id', $challenge->id )
        //     ->where('notifiable_type', 'App\Models\Challenge' )
        //     ->where('click_action', 'CHALLENGE_DETAIL_SCREEN' )
        //     ->where('title', 'Result Still Pending' )
        //     ->where('user_id', $challenge->user->id )
        //     ->exists();
        //     if(!$isNotification){
        //         # Send notification to creator 
        //         $notification = new Notification([
        //             'user_id' => $challenge->user->id,
        //             'title' => 'Result Still Pending',
        //             'body' => 'Result has been tied of challenge '.$challenge->title,
        //             'click_action' => 'CHALLENGE_DETAIL_SCREEN',
        //             'data_id' => $challenge->id,
        //         ]);
        //         $notify_user = User::find($challenge->user->id);
        //         Notifications::send($notify_user, new AskCandidate($challenge->id));
        //         $challenge->notifications()->save($notification);
        //     }
        //     $isNotification = Notification::where('notifiable_id', $challenge->id )
        //     ->where('notifiable_type', 'App\Models\Challenge' )
        //     ->where('click_action', 'CHALLENGE_DETAIL_SCREEN' )
        //     ->where('title', 'Result Still Pending' )
        //     ->where('user_id', 1 )
        //     ->exists();
        //     if(!$isNotification){
        //         $adminNotification = new Notification([
        //             'user_id' => 1,
        //             'title' => 'Result Still Pending',
        //             'body' => 'Result has been tied of the challenge '.$challenge->title,
        //             'click_action' => 'CHALLENGE_DETAIL_SCREEN',
        //             'data_id' => $challenge->id,
        //         ]);
        //         $challenge->notifications()->save($adminNotification);
        //     }
        // }
        return true;
    }

    public function getSubmitChallengeDetail(AcceptedChallenge $acceptedChallenge){
        try {
            $data['data'] = $acceptedChallenge;
            if($data['data']->submitChallenge->first()){
                collect($data)->map(function($item) {
                    $item['submited_challenge_id'] = $item->submitChallenge->id;
                    $item['title'] = $item->challenge->title;
                    $item['description'] = $item->challenge->description;
                    $item['submit_date'] = $item->submitChallenge->created_at->format('Y-m-d h:i A');
                    $item['files'] = $item->submitFiles->pluck('file');
                    $item['voteUp'] = $item->submitChallenge->votes()->where('user_id',auth()->id())
                    ->where('vote_up',true)->first();
                    $item['voteDown'] = $item->submitChallenge->votes()->where('user_id',auth()->id())
                    ->where('vote_down',true)->first();
                    $item['voteBtn'] = ($item->challenge->result_type == 'vote') ? true : false;
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
            $data['message'] = config('global.PREMIUM_USER_MESSAGE'); $res = 400;
            $data['premiumBtn'] = true;
            if(auth()->user()->is_premium){
                $data['premiumBtn'] = false;
                $data['message']='You have Already Submitted the Challenge!'; $res = 400;
                if(!$challenge->acceptedChallenges->where('user_id', auth()->id())->first()->submitChallenge()->exists()){
                    $data['message'] = 'No Video Uploaded!';
                    if ($challenge->acceptedChallenges->where('user_id', auth()->id())->first()->submitFiles()->exists()) {
                        $data['message'] = config('global.TIMEOUT_MESSAGE');
                        $after_date = $challenge->after_date;
                        if(now() >=  $challenge->start_time && now() <= $after_date){
                            $data['accepted_challenge_id'] = $challenge->acceptedChallenges->where('user_id', auth()->id())->first()->id;
                            $this->model->create($data);
                            $challenge->acceptedChallenges()->where('user_id', auth()->id())->first()->setStatus(Submitted());
                            $data['message']=config('global.SUBMIT_CHALLENGE_MESSAGE'); $res = 200;
                        }
                    }
                }
            }
        } catch (\Throwable $th) {
            $data['message'] = 'You need to Accept The Challenge First';
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
            $data['message'] = config('global.PREMIUM_USER_MESSAGE'); $res = 400;
            $data['premiumBtn'] = true;
            if(auth()->user()->is_premium){
                $data['premiumBtn'] = false;
                $isDonator = Amount::where('user_id', auth()->id())->where('challenge_id', $challenge->id)->exists();
                $message['message'] = config('global.CANNOT_ACCEPT_DONATOR_MESSAGE');
                if(!$isDonator){
                    $data['message'] = 'You can\'t add video due to Submited Challenge!'; $res = 400;
                    if(!optional($challenge->acceptedChallenges()->where('user_id', auth()->id())->first())->submitChallenge){
                        $files = $request->file;
                        $after_date = $challenge->after_date;
                        $data['message'] = config('global.TIMEOUT_MESSAGE'); $res = 400;
                        if(now() >=  $challenge->start_time && now() <= $after_date){
                            $file = uploadFile($files, SubmitChallengesPath(), null);
                            $records = new SubmitFile ([
                                'accepted_challenge_id' => $challenge->acceptedChallenges->where('user_id', auth()->id())->first()->id,
                                'file' => $file,
                                'created_at' => now(),
                            ]);
                            $challenge->acceptedChallenges()->where('user_id', auth()->id())->first()->submitFiles()->save($records);
                            $data['message'] = config('global.VIDEO_ADD_MESSAGE');$res = 200;
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
        if(now() >=  $file->acceptedChallenge->challenge->start_time && now() <= $file->acceptedChallenge->challenge->after_date){
            $file_name = $file->file;
            deleteFile($file_name, SubmitChallengesPath());
            $file->delete();
            return response(['message'=>config('global.VIDEO_DELETE_MESSAGE')]);
        }
        return response(['message'=>'You\'re out of time !']);
    }

}
