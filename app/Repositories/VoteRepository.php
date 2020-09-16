<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use App\Models\Challenge;
use App\Models\SubmitChallenge;
use Illuminate\Http\Request;
use DB;

class VoteRepository implements RepositoryInterface
{
    // model property on class instances
    protected $model;

    // Constructor to bind model to repo
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    // Get all instances of model
    public function all($with)
    {
        return $this->model->with($with)->get();
    }

    // create a new record in the database
    public function create(array $data)
    {
        return $this->model->create($data);
    }
    // Insert data in multiple rows
    public function createInArray(array $data, Model $model)
    {
        $this->model = $model;
        return $this->model->insert($data);
    }

    // update record in the database
    public function update(array $data, Model $model)
    {
        return $model->update($data);
    }

    // remove record from the database
    public function delete(Model $model)
    {
        return $model->delete();
    }

    // show the record with the given id
    public function show($id)
    {
        return $this->model->findOrFail($id);
    }

    // Get the associated model
    public function getModel()
    {
        return $this->model;
    }

    // Set the associated model
    public function setModel($model)
    {
        $this->model = $model;
        return $this;
    }

    // Eager load database relationships
    public function with($relations)
    {
        return $this->model->with($relations);
    }

    // Sort the records by priority
    public function sort(array $order)
    {
        foreach($order as $priority => $id){
            $data = ['priority' => $priority + 1];
            $this->update($data, $id);
        }
    }

    public function getResult($challenge,$total_votes)
    {
        $acceptedChallenges = $challenge->acceptedChallenges;
        $data = [];
        foreach ($acceptedChallenges as $key => $value) {
            $submitedChallenge = $value->submitChallenge;
            $total_vote_up = $this->model->where('submited_challenge_id', $submitedChallenge->id)
            ->where('vote_up',true)
            ->count();
            $total_vote_down = $this->model->where('submited_challenge_id',$submitedChallenge->id)
            ->where('vote_down',true)
            ->count();
            $data[$key]['id'] = $value->user->id;
            $data[$key]['name'] = $value->user->name ?? $value->user->username ;
            $data[$key]['votes'] = $total_vote_up - $total_vote_down;
            $result = (int)((($total_vote_up - $total_vote_down) / $total_votes)*100);
            $data[$key]['result'] = ( $result < 0 ? 0 : $result  );
        }
        return $data;
    }

    
}
