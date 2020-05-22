<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Amount extends Model
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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getAmountAttribute($value)
    {
        return $value ? config('global.currency').$value : null;
    }
}
