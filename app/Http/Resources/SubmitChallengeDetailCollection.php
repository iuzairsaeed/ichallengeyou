<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SubmitChallengeDetailCollection extends JsonResource
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
            'submited_challenge_id' => $this->submitChallenge[0]->id,
            'title' => $this->challenge->title,
            'submit_date' => $this->submitChallenge[0]->created_at->format('Y-m-d H:i A'),
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'avatar' => $this->user->avatar,
            ],
            'submit_videos' => [
                'file' => $this->submitFiles,
            ],
        ];
    }
}
