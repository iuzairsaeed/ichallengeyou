<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    protected $fillable = ['user_id', 'submited_challenge_id', 'vote'];

    public function submitChallenges()
    {
        return belongsTo(SubmitedChallenge::class);
    }

}
