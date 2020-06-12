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
            if ($challenge->acceptedChallenge->submitFiles->first()) {
                $data['message'] = 'You are out of time!';
                if(Carbon::now()->format('Y-d-m') <= $challenge->start_time->format('Y-d-m')){
                    $data['accepted_challenge_id'] = $challenge->acceptedChallenge->id;
                    $this->model->create($data);
                    $data['message']='You have Successfuly Submitted the Challenge!';
                }
            }
        } catch (\Throwable $th) {
            $data['message'] = 'You need to accept challenge first';
            $res = 400;
        }
        return response($data,$res);
    }

    public function getVideo(SubmitChallenges $file)
    {
        $file_name = $file->file;
        deleteFile($file_name, SubmitChallengesPath());
        $file->delete();
        return response(['message'=>'Video is Deleted!']);
    }

    public function addVideo(SubmitFile $fileModel, SubmitChallengeRequest $request)
    {
        $challenge_id = $request->challenge_id; 
        $message['message'] = 'You need to accept challenge first';
        $accepted_challenge = AcceptedChallenge::where('challenge_id' , $challenge_id)->where('user_id' , auth()->id())->with('challenge')->first();     
        if($accepted_challenge){
            if(Carbon::now()->format('Y-d-m') <= $accepted_challenge->challenge->start_time->format('Y-d-m')) {
                if($request->hasFile('file')){
                    foreach ($request->file as $key => $video) {
                        $files['file'][$key] = uploadFile($video, SubmitChallengesPath(), null);
                    }
                    foreach($files['file'] as $file){
                        $records[] = [
                            'accepted_challenge_id' => $accepted_challenge->id,
                            'file' => $file,
                            'created_at' => now(),
                        ]; 
                    }
                    $this->model = new ChallengeRepository($fileModel);
                    $this->model->createInArray($records);
                    $accepted_challenge->setStatus(Completed());
                    $message['message'] = 'Video has been Added!';
                    return response($message, 200);  
                }
            }
            $message['message'] = 'You are out of time!';
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
