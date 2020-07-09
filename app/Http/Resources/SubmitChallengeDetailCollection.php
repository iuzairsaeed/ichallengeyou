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
            'submited_challenge_id' => $this->submited_challenge_id,
            'title' => $this->title,
            'description' => $this->description,
            'submit_date' => $this->submit_date,
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'avatar' => $this->user->avatar,
            ],
            'file' => $this->files,
            'voteUp' => $this->voteUp ? true :false ,
            'voteDown' => $this->voteDown ? true :false ,
            'voteBtn' => $this->voteBtn,

        ];
    }
}
