<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime:'.Constant::DATE_FORMAT,
        'updated_at' => 'datetime:'.Constant::DATE_FORMAT,
    ];

    public function user()
    {
        return $this->belongsTo(User::class)->select(['id','name','username','avatar']);
    }

    public function challenge()
    {
        return $this->belongsTo(Challenge::class)->select(['id','title','user_id']);
    }
}
