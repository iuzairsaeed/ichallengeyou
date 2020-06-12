<?php

namespace App\Http\Controllers\Api;

use App\Repositories\ChallengeRepository;
use App\Http\Requests\Challenges\SubmitChallengeRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
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


    public function submitChallenge(Challenge $challenge)
    {
        $res = 200;
        try {
            $data['message'] = 'No Video Uploaded!';
            if ($challenge->acceptedChallenges->submitFiles->first()) {
                $data['message'] = 'You are out of time!';
                if(Carbon::now()->format('Y-d-m') <= $challenge->start_time->format('Y-d-m')){
                    $data['accepted_challenge_id'] = $challenge->acceptedChallenges->id;
                    $this->model->create($data);
                    $challenge->acceptedChallenges->setStatus(Submited());
                    $data['message']='You have Successfuly Submitted the Challenge!';
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
            $fileModel = $challenge->acceptedChallenges->submitFiles->first();
            $message['message'] = 'You are out of time!';
            if(Carbon::now()->format('Y-d-m') <= $challenge->start_time->format('Y-d-m')){
                foreach ($request->file as $key => $video) {
                    $files['file'][$key] = uploadFile($video, SubmitChallengesPath(), null);
                }
                foreach($files['file'] as $file){
                    $records[] = [
                        'accepted_challenge_id' => $challenge->acceptedChallenges->id,
                        'file' => $file,
                        'created_at' => now(),
                    ]; 
                }
                $this->model->createInArray($records, $fileModel);
                $message['message'] = 'Video has been Added!';
            }
        } catch (\Throwable $th) {
            $data['message'] = 'You need to accept challenge first';
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
