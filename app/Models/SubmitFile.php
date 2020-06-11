<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubmitFile extends Model
{
    protected $fillable = ['submit_challenge_id','file'];

    public function getFileAttribute($value)
    {
        $path = SubmitChallengesPath();
        return file_exists($path.$value) ? $path.$value : $path.'no-image.png';
    }

    public function getFileMimeAttribute()
    {
        return mime_content_type($this->file);
    }
}
