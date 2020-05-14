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
            'message' => 'Profile has been updated.'
        ], 200);
    }

    function updateAvatar(AvatarUpdateRequest $request)
    {
        $user = auth()->user();
        $data = $request->all();

        if($request->hasFile('avatar')){
            $deleteFile = $user->getAttributes()['avatar'] != 'no-avatar.png' ? $user->avatar : null;
            $file_name = uploadFile($request->avatar, avatarPath(), $deleteFile);
            $data['avatar'] = $file_name;
        }
        $user->fill($data);
        $user->update();

        return response([
            'message' => 'Avatar has been updated.'
        ], 200);
    }
}
