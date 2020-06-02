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
            "amounts_sum" => $this->amounts_sum,
            'challenge' => [
                'title' => $this->challenge->title,
                'start_time' => $this->challenge->start_time,
                'file' => $this->challenge->file,
                'file_mime' => $this->challenge->file_mime,
            ],
        ];
    }
}
