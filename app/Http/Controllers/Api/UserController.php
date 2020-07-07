<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Auth\ProfileUpdateRequest;
use App\Http\Requests\Auth\AvatarUpdateRequest;
use App\Repositories\Repository;
use App\Models\User;

class UserController extends Controller
{
    protected $model;

    public function __construct(User $model)
    {
        $this->model = new Repository($model);
    }

    function updateProfile(ProfileUpdateRequest $request)
    {
        $user = auth()->user();
        $user->update($request->all());

        return response([
            'message' => 'Profile has been updated.',
            'user' => $user
        ], 200);
    }

    function updateAvatar(AvatarUpdateRequest $request)
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

        return response([
            'message' => 'Avatar has been updated.',
            'avatar' => avatarsPath().$file_name
        ], 200);
    }

    public function getAllUsers() {
        $data = User::get('username');
        collect($data)->map(function ($item) {
            $item['pass'] = 'secret';
        });
        return ($data);
        return response($data, 200);
    }
}
