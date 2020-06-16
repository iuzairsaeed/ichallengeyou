<?php

namespace App\Http\Controllers\Api;

use App\Repositories\ChallengeRepository;
use App\Http\Requests\Challenges\SubmitChallengeRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\SubmitChallengeCollection;
use App\Http\Resources\SubmitChallengeDetailCollection;
use Illuminate\Http\Request;
use App\Models\Challenge;
use App\Models\SubmitFile;
use App\Models\SubmitChallenge;
use App\Models\AcceptedChallenge;
use Carbon\Carbon;
use Exception;
class SubmitChallengeController extends Controller
{

    protected $model;

    public function __construct(SubmitChallenge $model) {
        $this->model = new ChallengeRepository($model);
    }


    public function getSubmitChallengerList(Challenge $challenge,Request $request){
        try {
            $data['data'] = $challenge->acceptedChallenges;
            $data['data'] = SubmitChallengeCollection::collection($data['data']);
            return response($data,200);
        } catch (\Throwable $th) {
            return response(['message'=>'No submited Challenge'],204);
        }
    }

    public function getSubmitChallengeDetail(AcceptedChallenge $acceptedChallenge){
        try {
            $data['data'] = $acceptedChallenge;
            $data = SubmitChallengeDetailCollection::collection($data);
            return response($data,200);
        } catch (\Exception $th) {
            return response($th,204);
        }
    }

    public function postSubmitChallenge(Challenge $challenge)
    {
        $res = 200;
        try {
            $data['message']='You have Already Submitted the Challenge!';
            if(!$challenge->acceptedChallenges->where('user_id', auth()->id())->first()->submitChallenge()->exists()){
                $data['message'] = 'No Video Uploaded!';
                if ($challenge->acceptedChallenges->where('user_id', auth()->id())->first()->submitFiles()->exists()) {
                    $data['message'] = 'You are out of time!';
                    if(Carbon::now()->format('Y-d-m') <= $challenge->start_time->format('Y-d-m')){
                        $data['accepted_challenge_id'] = $challenge->acceptedChallenges->where('user_id', auth()->id())->first()->id;
                        $this->model->create($data);
                        $challenge->acceptedChallenges()->where('user_id', auth()->id())->first()->setStatus(Submited());
                        $data['message']='You have Successfuly Submitted the Challenge!';
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
            $data['file'] = $challenge->acceptedChallenges->submitFiles;
            return response($data,200);
        } catch (\Throwable $th) {
            return response(['message'=>'No Video Found!'],400);
        }
    }

    public function addVideo(Challenge $challenge, SubmitChallengeRequest $request)
    {
        $res = 200;
        try {
            $files = $request->file;
            $message['message'] = 'You are out of time!';
            if(Carbon::now()->format('Y-d-m') >= $challenge->start_time->format('Y-d-m')){
                $file = uploadFile($files, SubmitChallengesPath(), null);
                $records = new SubmitFile ([
                    'accepted_challenge_id' => $challenge->acceptedChallenges->where('user_id', auth()->id())->first()->id,
                    'file' => $file,
                    'created_at' => now(),
                ]); 
                $challenge->acceptedChallenges()->where('user_id', auth()->id())->first()->submitFiles()->save($records);
                $message['message'] = 'Video has been Added!';
            }
        } catch (\Throwable $th) {
            $message['message'] = 'You need to accept challenge first';
            $res = 400;
        }    
        return response($message, 200);
    }
    
    public function deleteVideo(SubmitFile $file)
    {
        $file_name = $file->file;
        deleteFile($file_name, SubmitChallengesPath());
        $file->delete();
        return response(['message'=>'Video is Deleted!']);
    }

}
