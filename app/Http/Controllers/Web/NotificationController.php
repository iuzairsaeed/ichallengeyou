<?php

namespace App\Http\Controllers\Web;

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

    public function getNotifications(Request $request)
    {
        $orderableCols = ['created_at'];
        $searchableCols = [];
        $whereChecks = [];
        $whereOps = [];
        $whereVals = [];
        $with = ['challenge'];
        $withCount = [];

        $data = $this->model->getData($request, $with, $withCount, $whereChecks, $whereOps, $whereVals, $searchableCols, $orderableCols);
       
        $serial = ($request->start ?? 0) +1 ;
        collect($data['data'])->map(function ($item) use (&$serial) {
            $item['serial'] = $serial++;
            if($item->notifiable_type == ''){
                $item['file'] = $item->user->avatar;
                $item['data_id'] = $item->user_id;
            } else if(optional(optional($item->notifiable)->acceptedChallenge)->challenge){ 
                #ModelType = SubmitedChallenge
                $item['file'] = $item->notifiable->acceptedChallenge->challenge->file;
                $item['data_id'] = $item->notifiable->accepted_challenge_id;
            } else if(optional(optional($item->notifiable)->submitChallenges)->challenge) { 
                $item['file'] = $item->notifiable->submitChallenges->acceptedChallenge->challenge->file;
                $item['data_id'] = $item->notifiable->submitChallenges->accepted_challenge_id;
            }
            return $item;
        });
        // $data['data'] = NotificationCollection::collection($data['data']);
        return response($data, $data['response']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return view('notifications.index');
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
