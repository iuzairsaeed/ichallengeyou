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
}
