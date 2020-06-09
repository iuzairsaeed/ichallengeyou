<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function loadBalance()
    {
        $auth = paypalAuth();
        $paymentRecord = paypalDetail($auth['access_token']);
        return response($paymentRecord,200);
    }
}
