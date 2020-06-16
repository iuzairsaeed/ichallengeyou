<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Challenge;
use App\Notifications\AccountActivated;

class DashboardController extends Controller
{
    public function index()
    {
        $user = User::find(2);
        $user->notify(new AccountActivated);
        dd(1);

        $challenges = Challenge::all();
        $totalChallenges = $challenges->count();
        $approvedChallenges = Challenge::currentStatus('approved')->count();

        return view('dashboard')->with([
            'approvedChallenges' => $approvedChallenges,
            'totalChallenges' => $totalChallenges
        ]);
    }
}
