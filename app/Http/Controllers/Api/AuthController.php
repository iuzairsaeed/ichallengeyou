<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Web\Auth\RegisterController;
use App\Notifications\ForgotPasswordNotification;
use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Setting;
use Hash;

class AuthController extends Controller
{
    protected function response($user, $statusCode)
    {
        // Revoke previous tokens...
        $user->tokens()->delete();

        $token = $user->createToken('app-user')->plainTextToken;

        $data = [
            'user' => [
                'name' => $user->name,
                'username' => $user->username,
                'email' => $user->email,
                'avatar' => $user->avatar,
                'is_premium' => $user->is_premium ?? 0,
                'balance' => $user->balance ?? '',
            ],
            'token' => $token,
            'expiration_minutes' => (int)config('sanctum.expiration')
        ];

        return response($data, $statusCode);
    }

    function login(Request $request)
    {
        $user = User::where('username', $request->username)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response([
                'message' => 'These credentials do not match our records.'
            ], 401);
        }elseif(!$user->is_active){
            return response([
                'message' => 'Your account has been disabled. Please contact support.'
            ], 401);
        }

        return $this->response($user, 200);
    }

    function register(RegisterRequest $request, RegisterController $register)
    {
        $user = $register->create($request->all());

        return $this->response($user, 201);
    }

    function forgotPassword(ForgotPasswordRequest $request)
    {
        $user = User::where('username', $request->username)->first();

        if (!$user->is_active){
            return response([
                'message' => 'Your account has been disabled. Please contact support.'
            ], 404);
        }

        $newPassword = substr(md5(microtime()),rand(0,26),8);
        $user->password = Hash::make($newPassword);
        $user->update();

        $user->notify(new ForgotPasswordNotification($newPassword));

        return response([
            'message' => 'An email has been sent to your account with new password. You should receive it within 5 minutes.'
        ], 200);
    }

    function changePassword(ChangePasswordRequest $request)
    {
        $user = auth()->user();
        $user->password = Hash::make($request->password);
        $user->save();

        return $this->response($user, 200);
    }
}
