<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ChallengeAccepted extends JsonResource
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
            'id' => $this->challenge->id,
            'title' => $this->challenge->title,
            'start_time' => $this->challenge->start_time->format('d M, Y'),
            'file' => $this->challenge->file,
            'file_mime' => $this->challenge->file_mime,
            'status' => $this->status,
            'amounts_sum' => $this->amounts_sum,
        ];
    }
}
