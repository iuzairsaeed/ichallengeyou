<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Repository;
use App\Models\Challenge;

class ChallengeController extends Controller
{
    protected $model;

    public function __construct(Challenge $challenge)
    {
        $this->model = new Repository($challenge);
    }

    public function index()
    {
        return view('challenges.index');
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|min:2'
        ]);
        return $this->model->create($request->only($this->model->getModel()->fillable));
    }

    public function show($id)
    {
        return $this->model->show($id);
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|min:2'
        ]);
        $this->model->update($request->only($this->model->getModel()->fillable), $id);
        return $this->model->find($id);
    }

    public function destroy($id)
    {
        return $this->model->delete($id);
    }

    public function getRecords(Request $request)
    {
        $draw = $request->get('draw');
        $start = $request->get('start');

        $orderableCols = ['title'];
        $searchCols = ['title'];
        $mustChecks = [];
        $mustVals = [auth()->id()];
        $with = [];

        $data = $this->model->getData($request, $with, $mustChecks, $mustVals, $searchCols, $orderableCols);
        $records = $data['records'];
        $recordsFiltered = $data['recordsFiltered'];
        $totalRecords = $data['totalRecords'];

        $data = [];
        $serial = $start + 1;
        foreach($records as $record){
            $data[] = [
                'serial'    =>  '<span class="drag_me">'.$serial.'</span><div data-rowid="' . $record->id . '"></div>',
                'icon'      =>  '<i class="'. $record->icon .' danger font-medium-3"></i>',
                'title'      =>  $record->title,
                'actions'   =>  '<a class="success p-0 mr-2" title="Edit">
                                    <i class="ft-edit font-medium-3"></i>
                                </a>'
            ];
            $serial++;
        };
        $data = array(
            'draw' => $draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        );

        return json_encode($data);
    }
}
