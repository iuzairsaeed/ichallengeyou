<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Repository;
use App\Models\Challenge;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Favourite;

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
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|min:2'
        ]);
        $model->setStatus('status-name');
        return $this->model->create($request->only($this->model->getModel()->fillable));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Challenge $challenge
     * @return \Illuminate\Http\Response
     */
    public function show(Challenge $challenge)
    {
        $data = [
            'data' => $challenge
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
        $challenge->likes()->where('user_id', auth()->id())->delete();
        $like = new Like([
            'user_id' => auth()->id()
        ]);
        $challenge->likes()->save($like);
        return response('Success', 201);
    }

    /**
     * Unlike the specified resource.
     *
     * @param  \App\Models\Challenge $challenge
     * @return \Illuminate\Http\Response
     */
    public function unlike(Challenge $challenge)
    {
        $challenge->likes()->where('user_id', auth()->id())->delete();
        return response('Success', 204);
    }

    /**
     * Favourite the specified resource.
     *
     * @param  \App\Models\Challenge $challenge
     * @return \Illuminate\Http\Response
     */
    public function favourite(Challenge $challenge)
    {
        $favourite = $challenge->favourites()->where('user_id', auth()->id())->first();
        if($favourite){
            $favourite->delete();
        }else{
            $favourite = new Favourite([
                'user_id' => auth()->id()
            ]);
            $challenge->favourites()->save($favourite);
        }
        return response('Success', 204);
    }
}
