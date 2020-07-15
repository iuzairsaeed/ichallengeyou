<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    protected $fillable = ['user_id', 'submited_challenge_id', 'vote_down', 'vote_up'];

    protected $with = ['user'];

    public function submitChallenges()
    {
        return $this->belongsTo(SubmitChallenge::class, 'submited_challenge_id');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notifiable');
    }

}
