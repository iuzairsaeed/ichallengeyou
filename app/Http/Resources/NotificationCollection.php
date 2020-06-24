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
            'voter_id' => $this->notifiable->user_id ?? 0,
            'challenge_detail_id' => $this->notifiable->submitChallenges->accepted_challenge_id ?? 0,
            'submited_challenge_id' => $this->notifiable->accepted_challenge_id ?? 0,
            'title' => $this->title,
            'body' => $this->body,
            'created_at' => $this->created_at,
        ];
    }
}
