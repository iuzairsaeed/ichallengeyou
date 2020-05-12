<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Repository;
use App\Models\Category;

class CategoryController extends Controller
{
    protected $model;

    public function __construct(Category $category)
    {
        $this->model = new Repository($category);
    }

    public function index(Request $request)
    {
        $data = [
            'data' => $this->model->all()
        ];
        return response($data, 200);
    }
}
