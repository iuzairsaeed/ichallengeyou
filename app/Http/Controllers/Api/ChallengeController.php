<?php

namespace App\Http\Controllers\Api;

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

    public function getList(Request $request)
    {
        $orderableCols = ['created_at', 'title', 'user.name'];
        $searchableCols = ['title'];
        $whereChecks = [];
        $whereVals = [];
        $with = ['user'];

        $data = $this->model->getData($request, $with, $whereChecks, $whereVals, $searchableCols, $orderableCols);

        return response($data, 200);
    }
}
