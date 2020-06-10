<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $device_token = User::find(2)->device_token;
        fcm()->to([$device_token]) // $recipients must an array
        ->priority('normal')
        ->timeToLive(0)
        ->data([
            'title' => 'Test FCM',
            'body' => 'This is a test of FCM',
        ])
        ->notification([
            'title' => 'Test FCM',
            'body' => 'This is a test of FCM',
        ])
        ->send();
        return view('dashboard');
    }
}
