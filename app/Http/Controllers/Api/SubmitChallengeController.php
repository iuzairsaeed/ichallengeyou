<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\ChallengeRepository;
use App\Models\Challenge;
use App\Models\SubmitChallenge;
use App\Models\AcceptedChallenge;
use Illuminate\Support\Facades\Auth;

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
    public function store(Request $request)
    {
        
        $accepted_challenge_id = $request->accepted_challenge_id;

        if($request->hasFile('file')){
            foreach ($request->file as $key => $file) {
                $data['file'][$key] = uploadFile($file, challengesPath(), null);
            }
        }
        foreach($data['file'] as $d){
            $records[] = [
                'accepted_challenge_id' => $request->accepted_challenge_id,
                'file' => $d,
            ]; 
            
        }
        $this->model->createInArray($records);
        $submit = AcceptedChallenge::where('id' , $accepted_challenge_id)->first();
        if($request->submit){
            $submit->setStatus(Completed());
        }
        return response('Challenge Submited!',200);
        return ($submit);
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
