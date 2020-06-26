<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BidCollection extends JsonResource
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
            'id'=> $this->id,
            'user_id'=> $this->user->id,
            'user_name'=> $this->user->name,
            'user_avatar'=> $this->user->avatar,
            'bid_amount'=> $this->bid_amount,
        ];
    }
}
