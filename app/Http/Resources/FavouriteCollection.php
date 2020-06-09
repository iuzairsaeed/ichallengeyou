<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FavouriteCollection extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "challenge_id" => $this->challenge->id,
            'challenge_title' => $this->challenge->title,
            'challenge_start_time' => $this->challenge->start_time,
            'challenge_file' => $this->challenge->file,
            'challenge_file_mime' => $this->challenge->file_mime,
            "amounts_sum" => $this->amounts_sum,
        ];
    }
}
