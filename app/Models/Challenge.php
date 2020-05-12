<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\ModelStatus\HasStatuses;

class Challenge extends Model
{
    use SoftDeletes, HasStatuses;

    protected $guarded = [];

    protected $hidden = [
        'user_id', 'updated_at', 'deleted_at'
    ];

    protected $casts = [
        'start_time' => 'datetime:m-d-Y h:m A',
        'created_at' => 'datetime:m-d-Y h:m A',
        'updated_at' => 'datetime:m-d-Y h:m A',
        'deleted_at' => 'datetime:m-d-Y h:m A',
    ];

    public function user()
    {
        return $this->belongsTo(User::class)->select(['id','name','username']);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function favourites()
    {
        return $this->hasMany(Favourite::class);
    }

    public function donations()
    {
        return $this->hasMany(Donation::class);
    }
}
