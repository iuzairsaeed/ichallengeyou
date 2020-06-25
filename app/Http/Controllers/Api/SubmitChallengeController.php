<?php

namespace App\Http\Controllers\Api;

use App\Repositories\ChallengeRepository;
use App\Http\Requests\Challenges\SubmitChallengeRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\SubmitChallengeCollection;
use App\Http\Resources\SubmitedVideoCollection;
use App\Http\Resources\SubmitChallengeDetailCollection;
use Illuminate\Http\Request;
use App\Models\Challenge;
use App\Models\SubmitFile;
use App\Models\SubmitChallenge;
use App\Models\AcceptedChallenge;

class SubmitChallengeController extends Controller
{

    protected $model;

    public function __construct(SubmitChallenge $model) {
        $this->model = new ChallengeRepository($model);
    }


    public function getSubmitChallengerList(Challenge $challenge,Request $request){
        $acceptedChallengeModel = new AcceptedChallenge;
        $this->model = new ChallengeRepository($acceptedChallengeModel);
        try {
            $orderableCols = [];
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
                $item['voteUp'] =  $item->submitChallenge->first()->votes()->where('vote_up' , true)->count();
                $item['voteDown'] =  $item->submitChallenge->first()->votes()->where('vote_down' , true)->count();
            });
            $data['data'] = SubmitChallengeCollection::collection($data['data']);
            return response($data,200);
        } catch (\Exception $th) {
            return ;
            return response(['message'=>$th->getMessage()],400);
        }
    }

    public function getSubmitChallengeDetail(AcceptedChallenge $acceptedChallenge){
        try {
            $data['data'] = $acceptedChallenge;
            $data = SubmitChallengeDetailCollection::collection($data);
            return response($data,200);
        } catch (\Exception $th) {
            return response($th->getMessage(),204);
        }
    }

    public function postSubmitChallenge(Challenge $challenge)
    {
        try {
            $data['message'] = 'Become one now, its 1 USD for god sake. Don’t be so cheap!'; $res = 400;
            if(auth()->user()->is_premium){
                $data['message']='You have Already Submitted the Challenge!'; $res = 400;
                if(!$challenge->acceptedChallenges->where('user_id', auth()->id())->first()->submitChallenge()->exists()){
                    $data['message'] = 'No Video Uploaded!';
                    if ($challenge->acceptedChallenges->where('user_id', auth()->id())->first()->submitFiles()->exists()) {
                        $data['message'] = 'You are out of time!';
                        $before_date = $challenge->start_time;
                        $after_date = $before_date->addDays($challenge->duration_days)
                        ->addHours($challenge->duration_hours)
                        ->addMinutes($challenge->duration_minutes);
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
            return response(['message'=>'No Video Found!'],204);
        }
    }

    public function addVideo(Challenge $challenge, SubmitChallengeRequest $request)
    {
        try {
            $data['message'] = 'Become one now, its 1 USD for god sake. Don’t be so cheap!'; $res = 400;
            if(auth()->user()->is_premium){
                $data['message'] = 'You can\'t add video due to Submited Challenge!'; $res = 400;
                if(!$challenge->acceptedChallenges()->where('user_id', auth()->id())->first()->submitChallenge->first()){
                    $files = $request->file;
                    $before_date = $challenge->start_time;
                    $after_date = $before_date->addDays($challenge->duration_days)
                    ->addHours($challenge->duration_hours)
                    ->addMinutes($challenge->duration_minutes);
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
        } catch (\Throwable $th) {
            $data['message'] = 'You need to accept challenge first';
            $res = 400;
        }    
        return response($data, $res);
    }
    
    public function deleteVideo(SubmitFile $file)
    {
        $file_name = $file->file;
        deleteFile($file_name, SubmitChallengesPath());
        $file->delete();
        return response(['message'=>'Video is Deleted!']);
    }

}
