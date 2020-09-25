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
        $totalChallenges = Challenge::count();
        $totalChallenges = Challenge::first()->status;
        $approvedChallenges = Challenge::currentStatus('approved')->count();

        return view('dashboard')->with([
            'approvedChallenges' => $approvedChallenges,
            'totalChallenges' => $totalChallenges
        ]);
    }
}
