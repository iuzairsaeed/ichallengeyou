<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
         'user_id', 'title', 'body', 'click_action','data_id'
    ];

    protected $hidden = [
        'user_id'
    ];

    protected $with = [
        'challenge'
    ];

    public function getCreatedAtAttribute($value)
    {
        return time_elapsed_string($value);
    }

    public function challenge()
    {
        return $this->belongsTo(Challenge::class);
    }

    public function vote()
    {
        return $this->belongsTo(Vote::class);
    }

    public function notifiable()
    {
        return $this->morphTo();
    }
}
