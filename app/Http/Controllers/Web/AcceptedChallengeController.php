<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\ChallengeRepository;
use App\Models\Vote;
use App\Models\Challenge;
use App\Models\SubmitChallenge;
use App\Models\AcceptedChallenge;

class AcceptedChallengeController extends Controller
{
    protected $model;

    public function __construct(AcceptedChallenge $model)
    {
        $this->model = new ChallengeRepository($model);
    }

    public function voters(Challenge $challenge, Request $request)
    {
        $orderableCols = ['user_id', 'created_at'];
        $searchableCols = [];
        $whereChecks = ['challenge_id'];
        $whereOps = ['='];
        $whereVals = [$challenge->id];
        $with = ['user'];
        $withCount = [];
        $currentStatus = [];
        $withSums = [];
        $withSumsCol = [];
        $addWithSums = [];
        $whereHas = 'submitChallenge';

        $data = $this->model->getData($request, $with, $withCount, $whereHas, $withSums, $withSumsCol, $addWithSums, $whereChecks,
                                        $whereOps, $whereVals, $searchableCols, $orderableCols, $currentStatus);
        $serial = ($request->start ?? 0) + 1;
        collect($data["data"])->map(function ($item) use (&$serial) {
            $item['voter'] = $item->submitChallenge->votes[0];
            $item['serial'] = $serial++;
            return $item;
        });
        return response($data,200);
    }
    
    public function getAcceptors($id, Request $request)
    {
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

    public function getSubmitors(Challenge $challenge, Request $request)
    {
        $orderableCols = ['user_id', 'created_at'];
        $searchableCols = [];
        $whereChecks = ['challenge_id'];
        $whereOps = ['='];
        $whereVals = [$challenge->id];
        $with = ['user'];
        $withCount = [];
        $currentStatus = [];
        $withSums = [];
        $withSumsCol = [];
        $addWithSums = [];
        $whereHas = 'submitChallenge';

        $data = $this->model->getData($request, $with, $withCount, $whereHas, $withSums, $withSumsCol, $addWithSums, $whereChecks,
                                        $whereOps, $whereVals, $searchableCols, $orderableCols, $currentStatus);
        $serial = ($request->start ?? 0) + 1;
        collect($data['data'])->map(function ($item) use (&$serial) {
            $item['isWinner'] = $item->submitChallenge->first()->isWinner ? 'Winner' : '-';
            $item['serial'] = $serial++;
            return $item;
        });
        return response($data, 200);
    }
}
