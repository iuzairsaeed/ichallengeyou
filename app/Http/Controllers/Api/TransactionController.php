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
        $user = auth()->user();
        $amount = (float)$request->amount;
        $email = $request->paypal_id;
        try {
            $res = 400;
            $data['message'] = config('global.PROVIDE_EMAIL_IN_WITHDRAWAL_MESSAGE');
            if($email){
                $data['message'] =  config('global.PROVIDE_AMOUNT_IN_WITHDRAWAL_MESSAGE');
                if($amount){
                    $data['message'] =  config('global.INVALID_AMOUNT_IN_WITHDRAWAL_MESSAGE');
                    if($amount <= (float)$user->getAttributes()['balance']){
                        $auth = paypalAuth();
                        (string)$token = $auth['access_token'];
                        if(!$token){
                            return response('Token is not Verified' , 401);
                        }
                        $sentResponse = sendMoney($email,$amount,$token);
                        $pay_id = $sentResponse['batch_header']['payout_batch_id'];sleep(5);
                        $checkPayment = checkPayment($pay_id,$token);
                        $data['message'] = array_key_exists('errors',$checkPayment["items"][0]) ? 'The recipient for this payout does not have an account.' : 'success';
                        if($data['message'] == 'success'){
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
                            $data['message'] = 'You have withdrown '.config('global.CURRENCY').' '.$amount.'. Your total balance is '.($user->balance ?? config('global.CURRENCY').' 0') ;
                            $data['amount'] =  $user->balance ?? config('global.CURRENCY').' 0.00';
                            $res = 200;
                        }
                    }
                }
            }
        } catch (\Throwable $th) {
            return response($th, 400);
            return response(['message'=>'Invalid Transaction'], 400);
        }
        return response($data, $res);
    }

    public function history(Transaction $request)
    {
        try {
            $orderableCols = ['created_at'];
            $searchableCols = ['type'];
            $whereChecks = ['user_id','status'];
            $whereOps = ['=','='];
            $whereVals = [auth()->id(),'paid'];
            $with = ['challenge'];
            $withCount = [];
            $currentStatus = [];
            $withSums = [];
            $withSumsCol = [];
            $addWithSums = [];
            $whereHas = null;
            $withTrash = false;

            $data = $this->model->getData($request, $with, $withTrash, $withCount, $whereHas, $withSums, $withSumsCol, $addWithSums, $whereChecks,
                                        $whereOps, $whereVals, $searchableCols, $orderableCols, $currentStatus);
            collect($data['data'])->map(function ($item) {
                $item['year'] = $item->created_at->year;
                $item['month'] = $item->created_at->format('M');
                $item['day'] = $item->created_at->day;
                switch ($item->type) {
                    case 'load':
                        $item['reason'] = 'Load Balance';
                        $item['amount'] = config('global.CURRENCY').' '.$item['amount'];
                        break;
                    case 'won_challenge':
                        $item['reason'] = 'Won Challange';
                        $item['amount'] = config('global.CURRENCY').' '.$item['amount'];
                        break;
                    case 'withdraw':
                        $item['reason'] = 'Withdraw Balance';
                        $item['amount'] = config('global.CURRENCY').' -'.$item['amount'];
                        break;
                    case 'donate':
                        $item['reason'] = 'Donate on Challenge';
                        $item['amount'] = config('global.CURRENCY').' -'.$item['amount'];
                        break;
                    case 'create_challenge':
                        $item['reason'] = 'Created Challenge';
                        $item['amount'] = config('global.CURRENCY').' -'.$item['amount'];
                        break;
                    case 'miscellaneous':
                        $item['reason'] = 'Premium Cost';
                        $item['amount'] = config('global.CURRENCY').' -'.$item['amount'];
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
