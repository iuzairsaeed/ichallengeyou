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
            'data_id' => $this->data_id ?? 0,
            'title' => $this->title,
            'serial' => $this->serial,
            'body' => $this->body,
            'type' => $this->notifiable_type,
            'file' => $this->file ?? '',
            'click_action' => $this->click_action,
            'created_at' => $this->created_at,
        ];
    }
}
