<?php

namespace App\Http\Controllers\Api;

use App\Repositories\ChallengeRepository;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Challenge;
use App\Models\SubmitFile;
use App\Models\SubmitChallenge;
use App\Models\AcceptedChallenge;
use Carbon\Carbon;
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
        // 
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
    public function store(Request $request, SubmitFile $fileModel)
    {
        $submit = $request->submit;
        $challenge_id = $request->challenge_id; 
        $message['message'] = 'You need to accept challenge first';
        $accepted_challenge = AcceptedChallenge::where('challenge_id' , $challenge_id)->where('user_id' , auth()->id())->with('challenge')->first();     
        if($accepted_challenge){
            $message['message'] = 'You are out of time!';
            if(Carbon::now()->format('Y-d-m') <= $accepted_challenge->challenge->start_time->format('Y-d-m')){
                # Add Submit Challenge with ( status true | false ) 
                $data = [
                    'accepted_challenge_id' => $accepted_challenge->id,
                    'submit' => $submit,
                ];       
                $submited_challenge = $this->model->create($data);
                # Add Submited Videos if Request have Video 
                if($request->hasFile('file')){
                    foreach ($request->file as $key => $video) {
                        $files['file'][$key] = uploadFile($video, SubmitChallengesPath(), null);
                    }
                    foreach($files['file'] as $file){
                        $records[] = [
                            'submited_challenges_id' => $submited_challenge->id,
                            'file' => $file,
                        ]; 
                    }
                    $this->model = new ChallengeRepository($fileModel);
                    $this->model->createInArray($records);
                    $message['message'] = 'Video has been Added!';
                }
                # set status true | false
                if($submit){
                    $accepted_challenge->setStatus(Completed());
                    $message['message'] = 'Challenge Submited!';
                    return response($message,200);
                }
                return response($message,200);
            }
        }
        return response($message,200);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
    public function destroy($id)
    {
        //
    }
}
