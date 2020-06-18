<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NotificationCollection extends JsonResource
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
            'challenge' => [
                'id' => $this->challenge->id,
                'title' => $this->challenge->title,
                'file' => $this->challenge->file,
                'file_mime' => $this->challenge->file_mime,
            ],
            'title' => $this->title,
            'body' => $this->body,
            'created_at' => $this->created_at,
        ];
    }
}
