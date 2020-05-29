<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Constant;

class Comment extends Model
{
    protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime:'.Constant::DATE_FORMAT,
        'updated_at' => 'datetime:'.Constant::DATE_FORMAT,
    ];

    protected $hidden = [
        'user_id', 'challenge_id', 'updated_at'
    ];

    protected $with = [
        'user'
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
}
