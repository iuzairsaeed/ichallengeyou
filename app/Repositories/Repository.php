<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use DB;

class Repository implements RepositoryInterface
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

    // update record in the database
    public function update(array $data, Model $model)
    {
        return $model->update($data);
    }

    // remove record from the database
    public function delete(Model $model)
    {
        return $model->destroy();
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

    // Get data for datatable
    public function getData($request, $with, $withCount, $whereChecks, $whereOps, $whereVals, $searchableCols, $orderableCols)
    {
        $start = $request->start ?? 0;
        $length = $request->length ?? 10;
        $filter = $request->search;
        $order = $request->order;
        $search = optional($filter)['value'] ?? false;
        $sort = optional($order)[0]['column'] ?? false;
        $dir = optional($order)[0]['dir'] ?? false;
        $from = $request->date_from;
        $to = $request->date_to;

        $records = $this->model->with($with)->withCount($withCount);

        if($whereChecks){
            foreach($whereChecks as $key => $check){
                $records->where($check, $whereOps[$key] ?? '=', $whereVals[$key]);
            }
        }
        $recordsTotal = $records->count();

        if($from){
            $records->whereDate('created_at' ,'>=', $from);
        }
        if($to){
            $records->whereDate('created_at' ,'<=', $to);
        }

        if($search){
            $records->where(function($query) use ($searchableCols, $search){
                foreach($searchableCols as $col){
                    $query->orWhere($col, 'like' , "%$search%");
                }
            });
        }
        $recordsFiltered = $records->count();

        if($dir){
            if(in_array($sort, $orderableCols)){
                $orderBy = $sort;
            }else{
                $orderBy = $orderableCols[$sort];
            }
            $records->orderBy($orderBy, $dir);
        }else{
            $records->latest();
        }
        $records = $records->limit($length)->offset($start)->get();

        $message = 'Success';
        if($records->count() == 0){
            $message = 'No data available.';
        }

        return [
            'message' => $message,
            'recordsFiltered' => $recordsFiltered,
            'recordsTotal' => $recordsTotal,
            'data' => $records,
        ];
    }
}
