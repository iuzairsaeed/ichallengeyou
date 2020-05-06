<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Repository;
use App\Models\Challenge;

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
        return $challenge;
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
}
