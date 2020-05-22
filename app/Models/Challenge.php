<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\ModelStatus\HasStatuses;

class Challenge extends Model
{
    use SoftDeletes, HasStatuses;

    protected $fillable = [
        'user_id', 'title', 'description', 'start_time', 'duration_days', 'duration_hours', 'duration_minutes', 'file', 'location', 'amount',
    ];

    protected $hidden = [
        'user_id', 'updated_at', 'deleted_at', 'userReaction'
    ];

    protected $casts = [
        'start_time' => 'datetime:m-d-Y h:m A',
        'created_at' => 'datetime:m-d-Y h:m A',
        'updated_at' => 'datetime:m-d-Y h:m A',
        'deleted_at' => 'datetime:m-d-Y h:m A',
    ];

    protected $appends = [
        'status'
    ];

    public function getFileAttribute($value)
    {
        return challengesPath().$value;
    }

    public function getStatusAttribute()
    {
        return $this->status()->name;
    }

    public function user()
    {
        return $this->belongsTo(User::class)->select(['id','name','username']);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function userReaction()
    {
        return $this->hasOne(Reaction::class)->where('user_id', auth()->id());
    }

    public function likes()
    {
        return $this->hasMany(Reaction::class)->where('like', true);
    }

    public function unlikes()
    {
        return $this->hasMany(Reaction::class)->where('unlike', true);
    }

    public function favorites()
    {
        return $this->hasMany(Reaction::class)->where('favorite', true);
    }

    public function amounts()
    {
        return $this->hasMany(Amount::class);
    }
}
