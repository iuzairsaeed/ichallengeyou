<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ChallengeDonated extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return  [
            'sum' => $this->sum,
            'challenge' => [
                'title' => $this->challenge->title,
                'start_time' => $this->challenge->start_time,
                'file' => $this->challenge->file,
                'file_mime' => $this->challenge->file_mime,
            ],
        ];
    }
}
