<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use SoftDeletes, HasApiTokens, Notifiable;

    protected $fillable = [
        'name', 'username', 'email', 'password', 'avatar', 'balance',
    ];

    protected $hidden = [
        'updated_at', 'email_verified_at', 'password', 'remember_token', 'deleted_at'
    ];

    protected $casts = [
        'is_premium' => 'boolean',
        'is_active' => 'boolean',
        'email_verified_at' => 'datetime:m-d-Y h:m A',
        'created_at' => 'datetime:m-d-Y h:m A',
        'updated_at' => 'datetime:m-d-Y h:m A',
        'deleted_at' => 'datetime:m-d-Y h:m A',
    ];

    public function getAvatarAttribute($value)
    {
        return file_exists(avatarsPath().$value) ? avatarsPath().$value : avatarsPath().'no-avatar.png';
    }

    public function challenges()
    {
        return $this->hasMany(Challenge::class);
    }
}
