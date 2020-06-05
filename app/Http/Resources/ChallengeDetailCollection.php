<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ChallengeDetailCollection extends JsonResource
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
            'id' => $this->id,
            'title' => $this->title,
            'file' => $this->file,
            'file_mime' => $this->file_mime,
            'start_time' => $this->start_time,
            'amounts_sum' => $this->amounts_sum,
            'location' => $this->location,
            'like' => $this->userReaction->like ?? false,
            'unlike' => $this->userReaction->unlike ?? false,
            'favorite' => $this->userReaction->favorite ?? false,
            'days' => $this->duration_days,
            'hour' => $this->duration_hours,
            'minutes' => $this->duration_minutes,
            'initial_amount' => $this->initialAmount->amount,
            'donators' => $this->donations,
            'user' => [
                "id" => $this->user->id,
                "name" => $this->user->name,
                "avatar" => $this->user->avatar,
            ],
            'category' => $this->category,
            'buttons' => [
                'acceptBtn' => $this->acceptBtn ?? true,
                'submitBtn' => $this->submitBtn ?? true,
                'donateBtn' => $this->donateBtn ?? true,
                'editBtn' => $this->editBtn ?? false,
            ],
            
        ];
    }
}
