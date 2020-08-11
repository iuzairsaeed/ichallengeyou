<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Repositories\Repository;

class MessageController extends Controller
{
    protected $model;

    public function __construct(Setting $model)
    {
        $this->model = new Repository($model);
    }

    public function getList(Request $request)
    {
        $orderableCols = ['created_at', 'name', 'value'];
        $searchableCols = ['name'];
        $whereChecks = ['setting_type'];
        $whereOps = ['='];
        $whereVals = ['dialog'];
        $with = [];
        $withCount = [];

        $data = $this->model->getData($request, $with, $withCount, $whereChecks, $whereOps, $whereVals, $searchableCols, $orderableCols);

        $serial = ($request->start ?? 0) + 1;
        collect($data['data'])->map(function ($item) use (&$serial) {
            $item['serial'] = $serial++;
            $item['valueOriginal'] = $item->value;
            $item['value'] = (strlen($item->value) > 45) ? substr($item->value, 0, 50).'...' : $item->value;
            return $item;
        });

        return response($data, 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('messages.index');
    }

    public function update(Request $request, Setting $setting)
    {
        $this->validate($request, [
            'value' => 'required'
        ]);
        $data = [
            'value' => $request->value
        ];
        $this->model->update($data, $setting);
        return response('success');
    }
}
