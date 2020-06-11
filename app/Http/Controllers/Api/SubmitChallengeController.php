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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $challenge_id = $request->challenge_id; 
        $message['message'] = 'You need to accept challenge first';
        $accepted_challenge = AcceptedChallenge::where('challenge_id' , $challenge_id)->where('user_id' , auth()->id())->with('challenge')->first();     
        if($accepted_challenge){
            $message['message'] = 'You are out of time!';
            if(Carbon::now()->format('Y-d-m') <= $accepted_challenge->challenge->start_time->format('Y-d-m')){
                $SubmitChallenge = SubmitChallenge::where('accepted_challenge_id' ,$accepted_challenge->id )->first();
                $submitFile = SubmitFile::where('accepted_challenge_id', $accepted_challenge->id)->first();
                $message['message'] = $submitFile ? 'You have already submitted the challenge!' : 'No Video Found!' ; 
                if(!$SubmitChallenge && $submitFile){
                    $data = [
                        'accepted_challenge_id' => $accepted_challenge->id,
                    ];       
                    $submit = $this->model->create($data);
                    $message['message'] = 'Challenge Submited!';
                }
            }
        }
        return response($message,200);
    }

    public function getVideo(SubmitChallenges $file)
    {
        $file_name = $file->file;
        deleteFile($file_name, SubmitChallengesPath());
        $file->delete();
        return response(['message'=>'Video is Deleted!']);
    }

    public function addVideo(SubmitChallengeRequest $request, SubmitFile $fileModel)
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        
    }
}
