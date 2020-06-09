<?php

namespace App\Models;
use Spatie\ModelStatus\HasStatuses;
use Illuminate\Database\Eloquent\Model;
use App\Models\AcceptedChallenge;

class SubmitChallenge extends Model
{
    use HasStatuses;
    protected $fillable = ['accepted_challenge_id' , 'file'];

    protected $casts = [
        'created_at' => 'datetime:'.Constant::DATE_FORMAT,
        'updated_at' => 'datetime:'.Constant::DATE_FORMAT,
    ];

    public function acceptedChallenge()
    {
        return hasMany(AcceptedChallenge::class);
    }
}