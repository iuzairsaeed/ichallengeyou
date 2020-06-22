<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    protected $fillable = ['user_id', 'submited_challenge_id', 'vote_down', 'vote_up'];

    public function submitChallenges()
    {
        return $this->belongsTo(SubmitChallenge::class);
    }

}
