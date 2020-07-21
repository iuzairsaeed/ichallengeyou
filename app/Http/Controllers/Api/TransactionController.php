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

    public function withdraw(Request $request)
    {
        $pay_id = 'PAY-231456789456321dUZ';
        $user = auth()->user();
        $amount = (float)$request->amount;
        try {
            $res = 400;
            $data['message'] = "Please Enter any Amount";
            if($amount){
                $data['message'] = "Your withdrwal amount can not be greater than your current Balance.";
                if($amount <= (float)$user->getAttributes()['balance']){
                    $user->balance = ((float)$user->getAttributes()['balance'] - $amount);
                    $user->update();
                    $transaction = [
                        'user_id' => $user->id,
                        'challenge_id' => null,
                        'amount' => $amount,
                        'type' => 'withdraw',
                        'invoice_id' => $pay_id,
                    ];
                    $this->model->create($transaction);
                    $data['message'] = 'You have withdrown $'.$amount.'. Your total balance is '.($user->balance ?? config('global.CURRENCY').'0') ;
                    $data['amount'] =  $user->balance ?? config('global.CURRENCY').'0';
                    $res = 200;
                }
            }
        } catch (\Throwable $th) {
            return response(['message'=>'Invalid Transaction'], 400);
        }
        return response($data, $res);
    }

    public function history(Transaction $request)
    {
        try {
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
            $whereHas = null;
            $data = $this->model->getData($request, $with, $withCount, $whereHas, $withSums, $withSumsCol, $addWithSums, $whereChecks,
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
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
