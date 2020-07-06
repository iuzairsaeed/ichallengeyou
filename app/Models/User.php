<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Constant;

class User extends Authenticatable
{
    use SoftDeletes, HasApiTokens, Notifiable;

    protected $guarded = [];

    protected $hidden = [
        'updated_at', 'email_verified_at', 'password', 'remember_token', 'device_token'
    ];

    protected $casts = [
        'is_premium' => 'boolean',
        'is_active' => 'boolean',
        'email_verified_at' => 'datetime:'.Constant::DATE_FORMAT,
        'created_at' => 'datetime:'.Constant::DATE_FORMAT,
        'updated_at' => 'datetime:'.Constant::DATE_FORMAT,
    ];

    protected $with = [

    ];

    public function getAvatarAttribute($value)
    {
        $path = avatarsPath();
        return file_exists($path.$value) ? $path.$value : $path.'no-image.png';
    }

    public function getBalanceAttribute($value)
    {
        return $value ? config('global.CURRENCY').$value : null;
    }

    /**
     * Route notifications for the FCM channel.
     *
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return string
     */
    public function routeNotificationForFcm($notification)
    {
        return $this->device_token;
    }

    public function challenges()
    {
        return $this->hasMany(Challenge::class);
    }

    public function acceptedChallenges()
    {
        return $this->hasMany(AcceptedChallenge::class);
    }

    public function donations()
    {
        return $this->hasMany(Amount::class)->where('type', 'donation');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    public function bids()
    {
        return $this->hasMany(Bid::class);
    }
}
