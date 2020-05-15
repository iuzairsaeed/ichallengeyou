<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Challenges\CreateChallengeRequest;
use App\Repositories\Repository;
use App\Models\Challenge;
use App\Models\Comment;
use App\Models\Reaction;
use Carbon\Carbon;

class ChallengeController extends Controller
{
    protected $model;

    public function __construct(Challenge $model)
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
        $orderableCols = ['created_at', 'title', 'start_time', 'user.name'];
        $searchableCols = ['title'];
        $whereChecks = [];
        $whereVals = [];
        $with = ['user'];

        $data = $this->model->getData($request, $with, $whereChecks, $whereVals, $searchableCols, $orderableCols);

        $serial = ($request->start ?? 0) + 1;
        collect($data['data'])->map(function ($item) use (&$serial) {
            $item['serial'] = $serial++;
            $item['like'] = $item->userReaction->like ?? false;
            $item['unlike'] = $item->userReaction->unlike ?? false;
            $item['favorite'] = $item->userReaction->favorite ?? false;
            return $item;
        });
        return response($data, 200);
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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateChallengeRequest $request)
    {
        $data = $request->all();

        if($request->hasFile('file')){
            $data['file'] = uploadFile($request->file, challengesPath(), null);
        }
        $data['user_id'] = auth()->id();
        $data['start_time'] = Carbon::createFromFormat('m-d-Y h:m A', $request->start_time)->toDateTimeString();

        $this->model->create($data);
        return response(['message' => 'Challenge has been created.'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Challenge $challenge
     * @return \Illuminate\Http\Response
     */
    public function show(Challenge $challenge)
    {
        $challenge['like'] = $challenge->userReaction->like ?? false;
        $challenge['unlike'] = $challenge->userReaction->unlike ?? false;
        $challenge['favorite'] = $challenge->userReaction->favorite ?? false;
        $data = [
            'data' => $challenge,
        ];
        return response($data, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Challenge $challenge
     * @return \Illuminate\Http\Response
     */
    public function edit(Challenge $challenge)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Challenge $challenge
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Challenge $challenge)
    {
        $this->validate($request, [
            'name' => 'required|min:2'
        ]);
        $challenge->setStatus('status-name');
        $this->model->update($request->only($this->model->getModel()->fillable), $challenge);
        return $this->model->find($challenge->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Challenge $challenge
     * @return \Illuminate\Http\Response
     */
    public function destroy(Challenge $challenge)
    {
        return $this->model->delete($challenge);
    }

    /**
     * Comment on the specified resource.
     *
     * @param  \App\Models\Challenge $challenge
     * @return \Illuminate\Http\Response
     */
    public function comment(Challenge $challenge, Request $request)
    {
        $this->validate($request, [
            'text' => 'required|min:3|max:1000'
        ]);
        $comment = new Comment([
            'text' => $request->text,
            'user_id' => auth()->id()
        ]);
        $challenge->comments()->save($comment);
        return response('Success', 201);
    }

    /**
     * Like the specified resource.
     *
     * @param  \App\Models\Challenge $challenge
     * @return \Illuminate\Http\Response
     */
    public function like(Challenge $challenge)
    {
        $reaction = $challenge->userReaction;
        if(!$reaction){
            $reaction = new Reaction([
                'user_id' => auth()->id(),
                'like' => true,
            ]);
            $challenge->userReaction()->save($reaction);
        }else{
            $reaction->update([
                'like' => $reaction->like ? false : true,
                'unlike' => false
            ]);
        }
        return response(['message' => 'Reaction Updated!'], 200);
    }

    /**
     * Unlike the specified resource.
     *
     * @param  \App\Models\Challenge $challenge
     * @return \Illuminate\Http\Response
     */
    public function unlike(Challenge $challenge)
    {
        $reaction = $challenge->userReaction;
        if(!$reaction){
            $reaction = new Reaction([
                'user_id' => auth()->id(),
                'unlike' => true,
            ]);
            $challenge->userReaction()->save($reaction);
        }else{
            $reaction->update([
                'like' => false,
                'unlike' => $reaction->unlike ? false : true
            ]);
        }
        return response(['message' => 'Reaction Updated!'], 200);
    }

    /**
     * Favorite the specified resource.
     *
     * @param  \App\Models\Challenge $challenge
     * @return \Illuminate\Http\Response
     */
    public function favorite(Challenge $challenge)
    {
        $reaction = $challenge->userReaction;
        if(!$reaction){
            $reaction = new Reaction([
                'user_id' => auth()->id(),
                'favorite' => true,
            ]);
            $challenge->userReaction()->save($reaction);
        }else{
            $reaction->update([
                'favorite' => $reaction->favorite ? false : true
            ]);
        }
        return response(['message' => 'Challenge Added to Favorites!'], 200);
    }
}
