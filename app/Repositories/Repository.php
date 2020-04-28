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
    public function getData($request, $with, $whereChecks, $whereVals, $searchableCols, $orderableCols)
    {
        $start = $request->start ?? 0;
        $length = $request->length ?? 10;
        $filter = $request->search;
        $order = $request->order;
        $search = optional($filter)['value'] ?? false;
        $sort = optional($order)[0]['column'] ?? false;
        $dir = optional($order)[0]['dir'] ?? false;

        $records = $this->with($with);
        if($whereChecks){
            foreach($whereChecks as $key => $check){
                $records->where($check, $whereVals[$key]);
            }
        }
        $recordsTotal = $records->count();
        if($search){
            $records->where(function($query) use ($searchableCols, $search){
                foreach($searchableCols as $col){
                    $query->orWhere($col, 'like' , "%$search%");
                }
            });
        }
        $recordsFiltered = $records->count();

        if($dir){
            $records->orderBy($orderableCols[$sort], $dir);
        }else{
            $records->latest();
        }
        $records = $records->limit($length)->offset($start)->get();

        $serial = $start + 1;
        $records->map(function ($item) use (&$serial) {
            $item['serial'] = $serial++;
            return $item;
        });

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
