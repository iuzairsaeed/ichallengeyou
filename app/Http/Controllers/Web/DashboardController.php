<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Notifications\AccountActivated;

class DashboardController extends Controller
{
    public function index()
    {
        $user = User::find(2);
        $user->notify(new AccountActivated);

        return view('dashboard');
    }
}
