<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Constant;

class Amount extends Model
{
    protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime:'.Constant::DATE_FORMAT,
        'updated_at' => 'datetime:'.Constant::DATE_FORMAT,
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
        return $value ? config('global.CURRENCY').$value : null;
    }
}
