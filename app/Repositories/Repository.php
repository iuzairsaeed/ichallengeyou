<?php namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

class Repository implements RepositoryInterface
{
    // model property on class instances
    protected $model;

    // Constructor to bind model to repo
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    // Get data for datatable
    public function getData($request, $with, $mustChecks, $mustVals, $searchCols, $orderableCols)
    {
        $start = $request->get('start');
        $length = $request->get('length');
        $filter = $request->get('search');
        $order = $request->get('order');
        $search = (isset($filter['value']))? $filter['value'] : false;
        $sort = (isset($order[0]['column']))? (int) $order[0]['column'] : false;
        $dir = (isset($order[0]['dir']))? $order[0]['dir'] : false;

        $records = $this->with($with);
        if($mustChecks){
            foreach($mustChecks as $key => $check){
                $records = $records->where($check, $mustVals[$key]);
            }
        }
        if($search){
            $records = $records->where(function($query) use ($searchCols, $search){
                foreach($searchCols as $col){
                    $query->orWhere($col, 'like' , "%$search%");
                }
            });
        }
        $recordsFiltered = $records->count();

        $records = $records->orderBy($orderableCols[$sort], $dir)->limit($length)->offset($start)->get();

        $totalRecords = $records->count();
        return [
            'records' => $records,
            'recordsFiltered' => $recordsFiltered,
            'totalRecords' => $totalRecords
        ];
    }

    // Get all instances of model
    public function all()
    {
        return $this->model->all();
    }

    // create a new record in the database
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    // update record in the database
    public function update(array $data, $id)
    {
        $record = $this->model->find($id);
        return $record->update($data);
    }

    // remove record from the database
    public function delete($id)
    {
        return $this->model->destroy($id);
    }

    // show the record with the given id
    public function show($id)
    {
        return $this->model-findOrFail($id);
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

    public function sort(array $order)
    {
        foreach($order as $priority => $id){
            $data = ['priority' => $priority + 1];
            $this->update($data, $id);
        }
    }
}
