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
            'amounts_sum' => $this->amounts_sum,
            'like' => $this->userReaction->like ?? false,
            'unlike' => $this->userReaction->unlike ?? false,
            'favorite' => $this->userReaction->favorite ?? false,
            'file_mime' => $this->file_mime,
            'user' => [
                "id" => $this->user->id,
                "name" => $this->user->name,
                "avatar" => $this->user->avatar,
            ],
            'category' => $this->category,
            'buttons' => [
                'acceptBtn' => $this->acceptBtn,
                'donateBtn' => $this->donateBtn,
                'editBtn' => $this->editBtn,
            ],
        ];
    }
}
