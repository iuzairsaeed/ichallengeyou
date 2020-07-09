<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\ChallengeRepository;
use App\Models\Challenge;
use App\Models\SubmitChallenge;
use App\Models\AcceptedChallenge;

class AcceptedChallengeController extends Controller
{
    protected $model;

    public function __construct(Challenge $model)
    {
        $this->model = new ChallengeRepository($model);
    }
    
    public function getAcceptors($id, Request $request)
    {
        $model = new AcceptedChallenge;
        $this->model = new ChallengeRepository($model);
        $orderableCols = ['user_id', 'created_at'];
        $searchableCols = [];
        $whereChecks = ['challenge_id'];
        $whereOps = ['='];
        $whereVals = [$id];
        $with = ['user'];
        $withCount = [];
        $currentStatus = [];
        $withSums = [];
        $withSumsCol = [];
        $addWithSums = [];
        $whereHas = null;

        $data = $this->model->getData($request, $with, $withCount, $whereHas, $withSums, $withSumsCol, $addWithSums, $whereChecks,
                                        $whereOps, $whereVals, $searchableCols, $orderableCols, $currentStatus);

        $serial = ($request->start ?? 0) + 1;
        collect($data['data'])->map(function ($item) use (&$serial) {
            $item['serial'] = $serial++;
            return $item;
        });
        return response($data, 200);
    }

    public function getSubmitors($id, Request $request)
    {
        $model = new SubmitChallenge;
        $this->model = new ChallengeRepository($model);
        $orderableCols = ['created_at'];
        $searchableCols = [];
        $whereChecks = ['accepted_challenge_id'];
        $whereOps = ['='];
        $whereVals = [];
        $with = [];
        $withCount = [];
        $currentStatus = [];
        $withSums = [];
        $withSumsCol = [];
        $addWithSums = [];
        $whereHas = null;

        $data = $this->model->getData($request, $with, $withCount, $whereHas, $withSums, $withSumsCol, $addWithSums, $whereChecks,
                                        $whereOps, $whereVals, $searchableCols, $orderableCols, $currentStatus);

        $serial = ($request->start ?? 0) + 1;
        collect($data['data'])->map(function ($item) use (&$serial) {
            $item['serial'] = $serial++;
            return $item;
        });
        return response($data, 200);
    }
}
