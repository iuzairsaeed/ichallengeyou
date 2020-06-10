<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\ChallengeRepository;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function loadBalance(Request $request)
    {
        $user = Auth::User();
        $pay_id = $request->response['id'];
        $auth = paypalAuth();
        (string)$token = $auth['access_token'];
        $paymentRecord = paypalDetail($token , $pay_id);
        $amount = $paymentRecord['transactions'][0]['amount']['total'];
        if($paymentRecord['state'] == 'approved' && $paymentRecord['payer']['status'] == 'VERIFIED' ){
            if(!$user->is_premium){
                $user->is_premium = true;
                $amount = $amount - config('global.PREMIUM_COST');
            }
            $user->balance = (float)$user->getAttributes()['balance'] + $amount;
            $user->update();
            $data = [
                'message' => '$'.$amount.' has been credited to your account \n Your Total Amount is '.$user->balance,
                'amount' => $amount,
            ];
            return response($data , 200);
        }
        return response('$data' , 200); 
    }
}
