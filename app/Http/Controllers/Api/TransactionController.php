<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Repositories\ChallengeRepository;
use App\Http\Resources\TransactionCollection;
use Carbon\Carbon;

class TransactionController extends Controller
{
    protected $model;

    public function __construct(Transaction $model)
    {
        $this->model = new ChallengeRepository($model);
    }

    public function history(Transaction $request)
    {
        $orderableCols = ['created_at'];
        $searchableCols = ['type'];
        $whereChecks = ['user_id'];
        $whereOps = ['='];
        $whereVals = [auth()->id()];
        $with = ['challenge'];
        $withCount = [];
        $currentStatus = [];
        $withSums = [];
        $withSumsCol = [];
        $addWithSums = [];

        $data = $this->model->getData($request, $with, $withCount, $withSums, $withSumsCol, $addWithSums, $whereChecks,
                                        $whereOps, $whereVals, $searchableCols, $orderableCols, $currentStatus);
        collect($data['data'])->map(function ($item) {
            $item['year'] = $item->created_at->year;
            $item['month'] = $item->created_at->month;
            $item['date'] = $item->created_at->day;
        });
        $data['data'] = TransactionCollection::collection($data['data']);
        return response($data, 200);

    
    }
}
