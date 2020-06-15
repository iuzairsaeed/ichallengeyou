<?php

namespace App\Http\Controllers\Api;

use App\Repositories\ChallengeRepository;
use App\Http\Requests\Challenges\SubmitChallengeRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\SubmitChallengeCollection;
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


    public function getSubmitChallenge(Challenge $challenge,Request $request){
        try {
            $data['data'] = $challenge->acceptedChallenges;
            $data['data'] = SubmitChallengeCollection::collection($data['data']);
            return response($data,200);
        } catch (\Throwable $th) {
            return response(['message'=>'No submited Challenge'],204);
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
                    if(Carbon::now()->format('Y-d-m') >= $challenge->start_time->format('Y-d-m')){
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
            $fileModel = $challenge->acceptedChallenges()->where('user_id', auth()->id())->first()->submitFiles;
            $message['message'] = 'You are out of time!';
            if(Carbon::now()->format('Y-d-m') <= $challenge->start_time->format('Y-d-m')){
                foreach ($request->file as $key => $video) {
                    $files['file'][$key] = uploadFile($video, SubmitChallengesPath(), null);
                }
                foreach($files['file'] as $file){
                    $records[] = [
                        'accepted_challenge_id' => $challenge->acceptedChallenges->where('user_id', auth()->id())->first()->id,
                        'file' => $file,
                        'created_at' => now(),
                    ]; 
                }
                $model = new SubmitFile;
                $this->model->createInArray($records, $model);
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
