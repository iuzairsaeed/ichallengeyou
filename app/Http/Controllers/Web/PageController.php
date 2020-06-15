<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function home(Request $request)
    {
        if (! $request->expectsJson()) {
            return redirect('login');
        }
        return response(['message' => 'Not Found'], 404);
    }

    public function terms_conditions()
    {
        $text = config('global.TERMS_CONDITIONS');
        return view('pages.terms_conditions', compact('text'));
    }
}
