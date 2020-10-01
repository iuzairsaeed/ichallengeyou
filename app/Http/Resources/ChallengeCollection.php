<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ChallengeCollection extends JsonResource
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
            'file' => $this->file,
            'amounts_sum' => $this->amounts_sum,
            'amounts_trend_sum' => $this->amounts_trend_sum,
            'like' => $this->userReaction->first()->like ?? false,
            'like_count' => format_number_in_k_notation($this->likes->count()),
            'unlike' => $this->userReaction->first()->unlike ?? false,
            'unlike_count' => format_number_in_k_notation($this->unlikes->count()),
            'favorite' => $this->userReaction->first()->favorite ?? false,
            'file_mime' => $this->file_mime,
            'category_name' => $this->category->name ?? '',
        ];
    }
}
