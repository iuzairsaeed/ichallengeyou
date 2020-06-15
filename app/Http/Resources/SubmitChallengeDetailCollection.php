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
            'accepted_challenge_id' => $this->id,
            'submitedted_challenge_id' => $this->submitChallenge[0]->id,
            'title' => $this->challenge->title,
            'user' => $this->user,
            'submit_date' => $this->submitChallenge[0]->created_at->format('Y-m-d H:i A'),
            'submit_files' => $this->submitFiles,
        ];
    }
}
