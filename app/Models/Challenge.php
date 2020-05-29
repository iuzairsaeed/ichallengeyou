<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\ModelStatus\HasStatuses;
use App\Models\Constant;

class Challenge extends Model
{
    use SoftDeletes, HasStatuses;

    protected $guarded = [];

    protected $hidden = [
        'category_id', 'user_id', 'updated_at', 'deleted_at', 'userReaction'
    ];

    protected $casts = [
        'start_time' => 'datetime:'.Constant::DATE_FORMAT,
        'created_at' => 'datetime:'.Constant::DATE_FORMAT,
        'updated_at' => 'datetime:'.Constant::DATE_FORMAT,
        'deleted_at' => 'datetime:'.Constant::DATE_FORMAT,
    ];

    protected $appends = [
        'status'
    ];

    protected $with = [
        'user', 'category'
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

    public function category()
    {
        return $this->belongsTo(Category::class)->select(['id','name']);
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

    public function initialAmount()
    {
        return $this->hasOne(Amount::class)->where('type', 'initial');
    }

    public function donations()
    {
        return $this->hasMany(Amount::class)->where('type', 'donation');
    }

    public function amounts()
    {
        return $this->hasMany(Amount::class);
    }

    public function acceptedChallenges()
    {
        return $this->belongsToMany(AcceptedChallenge::class);
    }
}
