<?php

namespace App\Models;
use Spatie\ModelStatus\HasStatuses;
use Illuminate\Database\Eloquent\Model;

class SubmitChallenge extends Model
{
    use HasStatuses;
    protected $fillable = ['accepted_challenge_id' , 'file'];

    protected $casts = [
        'created_at' => 'datetime:'.Constant::DATE_FORMAT,
        'updated_at' => 'datetime:'.Constant::DATE_FORMAT,
    ];

    public function acceptedChallenges()
    {
        return belongsTo(AcceptedChallenge::class);
    }

    public function votes()
    {
        return hasMany(Vote::class);
    }

}
