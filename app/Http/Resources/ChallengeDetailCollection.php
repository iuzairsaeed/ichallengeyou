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
            'status' => $this->status,
            'title' => $this->title,
            'description' => $this->description,
            'result_type' => $this->result_type,
            'file' => $this->file,
            'file_mime' => $this->file_mime,
            'start_time' => $this->start_time->format('d M, Y - h:m A'),
            'amounts_sum' => $this->amounts_sum,
            'location' => $this->location,
            'like' => $this->userReaction->first()->like ?? false,
            'like_count' => format_number_in_k_notation($this->likes->count()),
            'unlike' => $this->userReaction->first()->unlike ?? false,
            'unlike_count' => format_number_in_k_notation($this->unlikes->count()),
            'favorite' => $this->userReaction->first()->favorite ?? false,
            'days' => $this->duration_days,
            'hour' => $this->duration_hours,
            'minutes' => $this->duration_minutes,
            'initial_amount' => $this->initialAmount->amount,
            'donors' => $this->donations->count() ?? 0,
            'creator_name' => $this->user->name ?? $this->user->username,
            'creator_avatar' => $this->user->avatar,
            'category_id' => $this->category->id,
            'category_name' => $this->category->name,
            'buttons' => [
                'acceptBtn' => $this->acceptBtn ?? true,
                'submitBtn' => $this->submitBtn ?? false,
                'donateBtn' => $this->donateBtn ?? true,
                'editBtn' => $this->editBtn ?? false,
                'bidBtn' => $this->bidBtn ?? true,
                'reviewBtn' => $this->reviewBtn ?? false,
            ],
        ];
    }
}
