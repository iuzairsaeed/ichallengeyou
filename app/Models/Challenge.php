<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\ModelStatus\HasStatuses;
use App\Models\Constant;
use Illuminate\Faceades\Auth;

class Challenge extends Model
{
    use SoftDeletes, HasStatuses;

    protected $fillable = ['user_id', 'category_id', 'title', 'description', 'start_time', 'result_type', 'duration_days', 'duration_hours', 'duration_minutes', 'file', 'location'];

    protected $hidden = [
        'category_id', 'user_id', 'updated_at', 'deleted_at'
    ];

    protected $casts = [
        'start_time' => 'datetime:'.Constant::DATE_FORMAT,
        'created_at' => 'datetime:'.Constant::DATE_FORMAT,
        'updated_at' => 'datetime:'.Constant::DATE_FORMAT,
        'deleted_at' => 'datetime:'.Constant::DATE_FORMAT,
    ];

    protected $appends = [
        'status', 'file_mime'
    ];

    protected $with = [
        'user', 'category'
    ];

    public function getFileAttribute($value)
    {
        $path = challengesPath();
        return file_exists($path.$value) ? $path.$value : $path.'no-image.png';
    }

    public function getFileMimeAttribute()
    {
        return mime_content_type($this->file);
    }

    public function getStatusAttribute()
    {
        return $this->status()->name;
    }

    public function getAfterDateAttribute() {
        $before_date = $this->start_time;
        $after_date = $before_date->addDays($this->duration_days)
        ->addHours($this->duration_hours)
        ->addMinutes($this->duration_minutes);
        return $after_date;
    }

    public function user()
    {
        return $this->belongsTo(User::class)->select(['id','name','username','avatar']);
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
        return $this->morphMany(Reaction::class, 'reactionable');
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
        return $this->hasMany(Amount::class)->where('type', 'donation')->select('amount','user_id','challenge_id');
    }

    public function amounts()
    {
        return $this->hasMany(Amount::class);
    }
    public function acceptedChallenges()
    {
        return $this->hasMany(AcceptedChallenge::class,'challenge_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function bids()
    {
        return $this->hasMany(Bid::class);
    }
    
}
