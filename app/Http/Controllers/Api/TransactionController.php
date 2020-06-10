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
            $item['month'] = $item->created_at->format('F');
            $item['day'] = $item->created_at->day;
            $item['amount'] = config('global.CURRENCY').$item->amount;
            switch ($item->type) {
                case 'load':
                    $item['reason'] = 'Load Balance';
                    break;
                case 'won_challenge':
                    $item['reason'] = 'Won Challange';
                    break;
                case 'withdraw':
                    $item['reason'] = 'Withdraw Balance';
                    $item['amount'] = '-'.$item['amount'];
                    break;
                case 'donate':
                    $item['reason'] = 'Donate on Challenge';
                    $item['amount'] = '-'.$item['amount'];
                    break;
                case 'create_challenge':
                    $item['reason'] = 'Created Challenge';
                    $item['amount'] = '-'.$item['amount'];
                    break;
                case 'miscellaneous':
                    $item['reason'] = 'Premium Cost';
                    $item['amount'] = '-'.$item['amount'];
                    break;
            }
            $item['type'] = ($item->type == 'load' || $item->type == 'won_challenge') ? 1 : 0;
        });
        $data['data'] = TransactionCollection::collection($data['data']);
        return response($data, 200);

    
    }
}
