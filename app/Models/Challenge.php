<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Challenge extends Model
{
    protected $guarded = [];

    protected $casts = [
        'start_time' => 'datetime:m-d-Y h:m A',
        'created_at' => 'datetime:m-d-Y h:m A',
        'updated_at' => 'datetime:m-d-Y h:m A',
    ];

    public function user()
    {
        return $this->belongsTo(User::class)->select(['id','name','username']);
    }
}
