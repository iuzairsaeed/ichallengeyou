<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AskCandidate extends Model
{
    protected $casts = [
        'created_at' => 'datetime:'.Constant::DATE_FORMAT,
        'updated_at' => 'datetime:'.Constant::DATE_FORMAT,
    ];

    protected $fillable = ['user_id','challenge_id','vote','updated_at'];

}
