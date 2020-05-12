<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reaction extends Model
{
    protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime:m-d-Y h:m A',
        'updated_at' => 'datetime:m-d-Y h:m A',
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function challenge()
    {
        return $this->belongsTo(Challenge::class);
    }
}
