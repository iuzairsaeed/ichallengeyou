<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ChallengeList extends JsonResource
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
            'id' => $this->id,
            'status' => $this->status,
            'amounts_sum' => $this->amounts_sum,
            'file' => $this->file,
            'file_mime' => $this->file_mime,
            'user' => $this->user,
            'created_at' => $this->created_at,
        ];
    }
}
