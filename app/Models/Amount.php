<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Constant;

class Amount extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime:'.Constant::DATE_FORMAT,
        'updated_at' => 'datetime:'.Constant::DATE_FORMAT,
    ];

    protected $hidden = [
        'updated_at'
    ];

    public function challenge()
    {
        return $this->belongsTo(Challenge::class)->withTrashed();
    }


    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed()->select(['id','name','avatar']);
    }

}
