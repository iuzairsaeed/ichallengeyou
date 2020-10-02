<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;
use App\Http\Resources\NotificationCollection;
use App\Repositories\Repository;

class NotificationController extends Controller
{
    protected $model;

    public function __construct(Notification $model)
    {
        $this->model = new Repository($model);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $orderableCols = ['created_at'];
        $searchableCols = [];
        $whereChecks = ['user_id'];
        $whereOps = ['='];
        $whereVals = [auth()->id()];
        $with = ['challenge'];
        $withCount = [];

        $data = $this->model->getData($request, $with, $withCount, $whereChecks, $whereOps, $whereVals, $searchableCols, $orderableCols);
        collect($data['data'])->map(function ($item) {
            if($item->notifiable_type == ''){
                $item['file'] = $item->user->avatar;
                $item['data_id'] = $item->user_id;
            } else if(optional(optional($item->notifiable)->acceptedChallenge)->challenge){
                $item['file'] = $item->notifiable->acceptedChallenge->challenge->file;
                $item['data_id'] = $item->data_id;
            } else if(optional(optional($item->notifiable)->submitChallenges)->challenge) {
                $item['file'] = $item->notifiable->submitChallenges->acceptedChallenge->challenge->file;
                $item['data_id'] = $item->notifiable->submitChallenges->accepted_challenge_id;
            }
            if($item['type'] == "App\Models\Challenge"){
                $item['file'] = $item->user->avatar;
                $item['data_id'] = $item->data_id;
            }
            if($item['type'] == `App\Models\Transaction`){
                $item['file'] = $item->user->avatar;
                $item['data_id'] = $item->data_id;
            }
            if($item['click_action'] == "ASK_RESULT_DIALOG"){
                $item['file'] = $item->user->avatar;
                $item['data_id'] = $item->data_id;
            }
        });
        $data['data'] = NotificationCollection::collection($data['data']);
        return response($data, $data['response']);
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
        //
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
