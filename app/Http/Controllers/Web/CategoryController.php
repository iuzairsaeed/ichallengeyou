<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Repositories\Repository;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $model;

    public function __construct(Category $model)
    {
        $this->model = new Repository($model);
    }

    public function getList(Request $request)
    {
        $orderableCols = ['created_at', 'name', 'value'];
        $searchableCols = ['name'];
        $whereChecks = [];
        $whereOps = [];
        $whereVals = [];
        $with = [];
        $withCount = [];

        $data = $this->model->getData($request, $with, $withCount, $whereChecks, $whereOps, $whereVals, $searchableCols, $orderableCols);

        $serial = ($request->start ?? 0) + 1;
        collect($data['data'])->map(function ($item) use (&$serial) {
            $item['serial'] = $serial++;
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
        return view('category.index');
    }

    public function update(Request $request, Category $category)
    {
        $this->validate($request, [
            'value' => 'required'
        ]);
        $data = [
            'name' => $request->value
        ];
        $this->model->update($data, $category);
        return response('success');
    }

    public function destroy(Setting $setting)
    {
        try {
            $this->model->delete($setting);
            return redirect('/category')->with('success', 'Category Deleted Successfully');
        } catch (\Throwable $th) {
            throw $th;
        } 
    }
}
