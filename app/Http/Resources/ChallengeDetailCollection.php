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
            'description' => $this->description,
            'file' => $this->file,
            'file_mime' => $this->file_mime,
            'start_time' => $this->start_time->format('d M, Y - h:m A'),
            'amounts_sum' => $this->amounts_sum,
            'location' => $this->location,
            'like' => $this->userReaction->first()->like ?? false,
            'unlike' => $this->userReaction->first()->unlike ?? false,
            'favorite' => $this->userReaction->first()->favorite ?? false,
            'days' => $this->duration_days,
            'hour' => $this->duration_hours,
            'minutes' => $this->duration_minutes,
            'initial_amount' => $this->initialAmount->amount,
            'bids' => $this->bids->count() ?? 0,
            'creator_name' => $this->user->name,
            'creator_avatar' => $this->user->avatar,
            'category_id' => $this->category->id,
            'category_name' => $this->category->name,
            'donators' => $this->donations,
            'buttons' => [
                'acceptBtn' => $this->acceptBtn ?? true,
                'submitBtn' => $this->submitBtn ?? false,
                'donateBtn' => $this->donateBtn ?? true,
                'editBtn' => $this->editBtn ?? false,
                'bidBtn' => $this->bidBtn ?? true,
                'reviewBtn' => $this->reviewBtn ?? false,
                'submitedListBtn' => true,
            ],
        ];
    }
}
