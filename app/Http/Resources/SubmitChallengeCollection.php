<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SubmitChallengeCollection extends JsonResource
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
            'user' => $this->user,
            'submit_date' => $this->created_at->format('Y-m-d H:i A'),
            'voteUp' => $this->voteUp ?? 0,
            'voteDown' => $this->voteDown ?? 0,
            'isWinner' => $this->isWinner ?? false ,
        ];
    }
}
