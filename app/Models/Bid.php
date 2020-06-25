<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bid extends Model
{
    protected $fillable = [
        'user_id', 'challenge_id', 'bid_amount'
    ];

    public function user()
    {
        return $this->belongsTo(User::class)->select(['id','name','username','avatar']);
    }

    public function challenge()
    {
        return $this->belongsTo(Challenge::class)->select(['id','title','start_time','file','result_type']);
    }

}
