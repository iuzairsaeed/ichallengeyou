<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Auth\ChangePasswordRequest;
use Hash;

class ProfileController extends Controller
{
    public function index()
    {
        return view('auth.index');
    }

    public function showChangePasswordForm()
    {
        return view('auth.changePassword');
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        $user = auth()->user();
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->back()->with('success', 'Password has been updated.');
    }
}
