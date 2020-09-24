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
    protected function response($user, $statusCode, $message)
    {
        // Revoke previous tokens...
        $user->tokens()->delete();

        $token = $user->createToken('app-user')->plainTextToken;
        $data = [
            'message' => $message,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'email' => $user->email,
                'contact_number' => $user->contact_number ?? '',
                'currency' => config('global.CURRENCY') ?? '',
                'avatar' => $user->avatar,
                'is_premium' => $user->is_premium ?? false,
                'is_admin' => $user->is_admin ?? false,
                'balance' => $user->balance ?? config('global.CURRENCY').' 0.00',
            ],
            'token' => $token,
            'expiration_minutes' => (int)config('sanctum.expiration')
        ];

        return response($data, $statusCode);
    }

    function login(Request $request)
    {
        $this->validate($request, [
            'username' => 'required',
            'password' => 'required',
        ]);
        $user = User::where('username', $request->username)->orWhere('email', $request->username)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response([
                'message' => config('global.LOGIN_CREDENTIAL_MESSAGE')
            ], 400);
        }elseif(!$user->is_active){
            return response([
                'message' => config('global.LOGIN_DISABLE_MESSAGE')
            ], 400);
        }elseif(!$user->email_verified_at) {
            $user->sendEmailVerificationNotification();

            return response([
                'message' => config('global.EMAIL_VERIFY_MESSAGE')
            ], 202);
        }

        $user->platform = $request->platform;
        $user->device_token = $request->device_token;
        $user->update();
        return $this->response($user, 200, config('global.LOGIN_MESSAGE'));
    }

    function user()
    {
        return $this->response(auth()->user(), 200, 'Success');
    }

    function logout()
    {
        auth()->user()->tokens()->delete();

        return response([
            'message' => config('global.LOGOUT_MESSAGE')
        ], 200);
    }

    function register(RegisterRequest $request, RegisterController $register)
    {
        $user = $register->create($request->all());

        if(!$user->email_verified_at) {
            return response([
                'message' => config('global.EMAIL_VERIFY_MESSAGE')
            ], 202);
        }

        return $this->response($user, 201, config('global.REGISTER_MESSAGE'));
    }

    function forgotPassword(ForgotPasswordRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if(!$user){
            return response([
                'message' => config('global.FORGET_PASSWORD_INCORRECT_EMAIL_MESSAGE')
            ], 404);
        }
        if (!$user->is_active){
            return response([
                'message' => config('global.LOGIN_DISABLE_MESSAGE')
            ], 404);
        }

        $newPassword = substr(md5(microtime()),rand(0,26),8);
        $user->password = Hash::make($newPassword);
        $user->update();

        $user->notify(new ForgotPasswordNotification($newPassword));

        return response([
            'message' => config('global.FORGET_PASSWORD_CORRECT_EMAIL_MESSAGE')
        ], 200);
    }

    function changePassword(ChangePasswordRequest $request)
    {
        $user = auth()->user();
        $user->password = Hash::make($request->password);
        $user->save();

        return $this->response($user, 200, config('global.CHANGE_PASSWORD_MESSAGE'));
    }
}
