<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'name', 'username', 'email', 'password', 'avatar', 'balance', 'is_premium', 'is_active',
    ];

    protected $hidden = [
        'updated_at', 'email_verified_at', 'password', 'remember_token'
    ];

    protected $casts = [
        'is_premium' => 'boolean',
        'is_active' => 'boolean',
        'email_verified_at' => 'datetime:m-d-Y h:m A',
        'created_at' => 'datetime:m-d-Y h:m A',
        'updated_at' => 'datetime:m-d-Y h:m A',
    ];

    protected $with = [
        'challenges', 'donations'
    ];

    public function getAvatarAttribute($value)
    {
        return file_exists(avatarsPath().$value) ? avatarsPath().$value : avatarsPath().'no-avatar.png';
    }

    public function getBalanceAttribute($value)
    {
        return $value ? config('global.currency').$value : null;
    }

    public function challenges()
    {
        return $this->hasMany(Challenge::class);
    }

    public function donations()
    {
        return $this->hasMany(Amount::class)->where('type', 'donation');
    }
}
