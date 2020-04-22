<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Hash;
use App\Http\Controllers\Web\Auth\RegisterController;

class AuthController extends Controller
{
    protected function response($user)
    {
        $token = $user->createToken('app-user')->plainTextToken;
        return [
            'user_name' => $user->name,
            'user_email' => $user->email,
            'token' => $token,
            'expiration_minutes' => (int)config('sanctum.expiration')
        ];
    }
    function login(Request $request)
    {
        $user= User::where('username', $request->username)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response([
                'message' => 'These credentials do not match our records.'
            ], 404);
        }

        // Revoke previous tokens...
        $user->tokens()->delete();

        $response = $this->response($user);

        return response($response, 200);
    }

    function register(Request $request)
    {
        $data = $request->all();

        $register = new RegisterController;

        $validator = $register->validator($data);
        if ($validator->fails()) {
            return response([
                'message' => $validator->errors()->first()
            ], 400);
        }
        $user = $register->create($data);

        $response = $this->response($user);

        return response($response, 200);
    }
}
