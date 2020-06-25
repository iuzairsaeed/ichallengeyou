<?php

namespace App\Models;
use Spatie\ModelStatus\HasStatuses;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class SubmitChallenge extends Model
{
    use HasStatuses,Notifiable;
    protected $fillable = ['accepted_challenge_id' , 'file'];
    protected $with = [
        'acceptedChallenge'
    ];

    protected $casts = [
        'created_at' => 'datetime:'.Constant::DATE_FORMAT,
        'updated_at' => 'datetime:'.Constant::DATE_FORMAT,
    ];

    public function acceptedChallenge()
    {
        return $this->belongsTo(AcceptedChallenge::class);
    }

    public function votes()
    {
        return $this->hasMany(Vote::class, 'submited_challenge_id');
    }

    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notifiable');
    }

}
