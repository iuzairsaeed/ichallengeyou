<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Http\Requests\Auth\ProfileUpdateRequest;
use App\Notifications\ChallengeNotification;
use App\Models\Challenge;
use Hash;
use Notification;

class ProfileController extends Controller
{
    public function showProfileForm()
    {
        Notification::send(auth()->user(), new ChallengeNotification(1,"Uzair Saee Sufwan"));
        $user = auth()->user();
        return view('auth.profile', compact('user'));
    }

    public function profile(ProfileUpdateRequest $request)
    {
        $user = auth()->user();
        $data = $request->all();

        if($request->hasFile('avatar')){
            $deleteFile = $user->getAttributes()['avatar'] != 'no-image.png' ? $user->avatar : null;
            $file_name = uploadFile($request->avatar, avatarsPath(), $deleteFile);
            $data['avatar'] = $file_name;
        }
        $user->fill($data);
        $user->update();
        return redirect()->back()->with('success', 'Profile has been updated.');
    }

    public function showChangePasswordForm()
    {
        return view('auth.passwords.changePassword');
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        $user = auth()->user();
        $user->password = Hash::make($request->password);
        $user->update();

        return redirect()->back()->with('success', 'Password has been updated.');
    }
}
