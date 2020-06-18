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
                'title' => $this->challenge->title,
                'file' => $this->challenge->file,
            ],
            'title' => $this->title,
            'body' => $this->body,
            'created_at' => $this->created_at,
        ];
    }
}
