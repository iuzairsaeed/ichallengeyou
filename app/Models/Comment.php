<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Constant;

class Comment extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime:'.Constant::DATE_FORMAT,
        'updated_at' => 'datetime:'.Constant::DATE_FORMAT,
    ];

    protected $hidden = [
        'user_id', 'challenge_id', 'updated_at','parent_id'
    ];

    protected $with = [
        'user','userReaction'
    ];

    public function getCreatedAtAttribute($value)
    {
        return time_elapsed_string($value);
    }

    public function user()
    {
        return $this->belongsTo(User::class)->select(['id', 'name', 'avatar']);
    }

    public function challenge()
    {
        return $this->belongsTo(Challenge::class);
    }

    public function replies() {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    public function userReaction()
    {
        return $this->morphMany(Reaction::class, 'reactionable')->select(['reactionable_id','like','unlike']);
    }
}
